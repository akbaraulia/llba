<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\SystemSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with(['member', 'user', 'items'])->latest('purchase_date');

        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        $purchases = $query->paginate(10);

        return view('purchases.index', [
            'purchases' => $purchases,
        ]);
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('purchases.create', [
            'products' => $products,
        ]);
    }

    public function prepare(Request $request)
    {
        $data = $request->validate([
            'qty' => ['required', 'array'],
            'qty.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $productIds = collect(array_keys($data['qty']))->map(fn($id) => (int) $id)->all();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $total = 0;

        foreach ($data['qty'] as $productId => $qtyValue) {
            $qty = (int) $qtyValue;
            if ($qty <= 0) {
                continue;
            }

            $product = $products->get((int) $productId);
            if (! $product) {
                continue;
            }

            if ($qty > $product->stock) {
                return redirect()->route('purchases.create')
                    ->withInput()
                    ->withErrors([
                        'qty' => "Jumlah {$product->name} melebihi stok yang tersedia.",
                    ]);
            }

            $subtotal = (float) $product->price * $qty;
            $total += $subtotal;

            $items[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($items)) {
            return redirect()->route('purchases.create')->withErrors([
                'qty' => 'Pilih minimal 1 produk untuk diproses.',
            ]);
        }

        session([
            'checkout' => [
                'items' => $items,
                'total' => $total,
            ],
        ]);

        return redirect()->route('purchases.member');
    }

    public function memberForm(Request $request)
    {
        $checkout = session('checkout');
        if (! $checkout) {
            return redirect()->route('purchases.create')->withErrors([
                'checkout' => 'Silakan pilih produk terlebih dahulu.',
            ]);
        }

        $settings = SystemSetting::current();
        $phone = old('customer_phone');
        $member = $phone
            ? Member::with('user')->whereHas('user', fn($query) => $query->where('phone', $phone))->first()
            : null;
        $memberDirectory = Member::with('user')
            ->get()
            ->filter(fn($item) => $item->user && filled($item->user->phone))
            ->map(function ($item) {
                return [
                    'phone' => $item->user->phone,
                    'name' => $item->user->name,
                    'email' => $item->user->email,
                    'points' => (int) $item->points,
                    'max_redeem_percentage' => $item->max_redeem_percentage !== null
                        ? (float) $item->max_redeem_percentage
                        : null,
                ];
            })
            ->values();

        return view('purchases.member', [
            'checkout' => $checkout,
            'member' => $member,
            'memberDirectory' => $memberDirectory,
            'settings' => $settings,
        ]);
    }

    public function process(Request $request)
    {
        $checkout = session('checkout');
        if (! $checkout) {
            return redirect()->route('purchases.create')->withErrors([
                'checkout' => 'Sesi checkout tidak ditemukan.',
            ]);
        }

        $request->merge([
            'cash_paid' => $this->normalizeMoneyInput($request->input('cash_paid')),
        ]);

        $baseValidation = $request->validate([
            'member_status' => ['required', 'in:non_member,member'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'cash_paid' => ['required', 'numeric', 'min:0'],
            'points_used' => ['nullable', 'integer', 'min:0'],
        ]);

        $settings = SystemSetting::current();
        $isMember = $baseValidation['member_status'] === 'member';
        $totalBeforeDiscount = (float) $checkout['total'];
        $pointRedeemValue = max((float) $settings->point_redeem_value, 0.01);
        $pointEarnSpend = max((float) $settings->point_earn_spend, 0.01);
        $defaultMaxRedeemPercentage = max((float) $settings->default_max_redeem_percentage, 0);

        $member = null;
        $memberUser = null;
        $customerName = $baseValidation['customer_name'] ?? null;
        $customerPhone = $baseValidation['customer_phone'] ?? null;
        $customerEmail = $baseValidation['customer_email'] ?? null;
        $pointsUsed = 0;
        $maxPointsUsable = 0;
        $maxRedeemPercentage = $defaultMaxRedeemPercentage;
        $pointsDiscountAmount = 0;

        if ($isMember) {
            $request->validate([
                'customer_phone' => ['required', 'string', 'max:30'],
                'use_points' => ['required', 'in:yes,no'],
            ]);

            $customerPhone = $baseValidation['customer_phone'];
            $memberUser = User::with('memberProfile')
                ->where('phone', $customerPhone)
                ->where('role', 'member')
                ->first();

            if (! $memberUser || ! $memberUser->memberProfile) {
                return redirect()->route('purchases.member')
                    ->withInput()
                    ->withErrors([
                        'customer_phone' => 'Member tidak ditemukan. Masukkan nomor telepon member yang terdaftar.',
                    ]);
            }

            if ($memberUser) {
                $member = $memberUser->memberProfile;
                $customerName = $memberUser->name;
                $customerPhone = $memberUser->phone;
                $customerEmail = $memberUser->email;
            }

            if ($member) {
                $maxRedeemPercentage = $member->max_redeem_percentage !== null
                    ? (float) $member->max_redeem_percentage
                    : $defaultMaxRedeemPercentage;

                $maxDiscountAmount = $totalBeforeDiscount * ($maxRedeemPercentage / 100);
                $maxPointsByPercent = (int) floor($maxDiscountAmount / $pointRedeemValue);
                $maxPointsUsable = min($member->points, $maxPointsByPercent);
            }

            $usePoints = $request->string('use_points')->toString() === 'yes';
            $pointsUsed = $usePoints ? min((int) ($baseValidation['points_used'] ?? 0), $maxPointsUsable) : 0;
            $pointsDiscountAmount = $pointsUsed * $pointRedeemValue;
        }

        $totalAfterDiscount = max(0, $totalBeforeDiscount - $pointsDiscountAmount);
        $cashPaid = (float) $baseValidation['cash_paid'];

        if ($cashPaid < $totalAfterDiscount) {
            return redirect()->route('purchases.member')
                ->withInput()
                ->withErrors([
                    'cash_paid' => 'Total bayar kurang dari total yang harus dibayar.',
                ]);
        }

        $pointsEarned = $isMember ? (int) floor($totalAfterDiscount / $pointEarnSpend) : 0;
        $changeAmount = $cashPaid - $totalAfterDiscount;
        $purchase = null;

        try {
            DB::transaction(function () use (&$purchase, $checkout, $request, $isMember, &$member, &$memberUser, $customerName, $customerPhone, $customerEmail, $totalBeforeDiscount, $totalAfterDiscount, $pointsUsed, $pointsEarned, $cashPaid, $changeAmount, $pointRedeemValue, $pointEarnSpend, $maxRedeemPercentage, $pointsDiscountAmount) {
                if ($member) {
                    $member = Member::whereKey($member->id)->lockForUpdate()->firstOrFail();

                    if ($pointsUsed > $member->points) {
                        throw ValidationException::withMessages([
                            'points_used' => 'Poin member sudah berubah, silakan ulangi perhitungan pembayaran.',
                        ]);
                    }
                }

                $purchase = Purchase::create([
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'user_id' => $request->user()->id,
                    'member_id' => $member?->id,
                    'purchase_date' => now(),
                    'customer_name' => $customerName,
                    'customer_phone' => $customerPhone,
                    'customer_email' => $customerEmail,
                    'is_member' => $isMember,
                    'total_before_discount' => $totalBeforeDiscount,
                    'total_after_discount' => $totalAfterDiscount,
                    'points_used' => $pointsUsed,
                    'points_earned' => $pointsEarned,
                    'point_redeem_value' => $pointRedeemValue,
                    'point_earn_spend' => $pointEarnSpend,
                    'max_redeem_percentage' => $maxRedeemPercentage,
                    'points_discount_amount' => $pointsDiscountAmount,
                    'cash_paid' => $cashPaid,
                    'change_amount' => $changeAmount,
                ]);

                foreach ($checkout['items'] as $item) {
                    $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                    if ($product->stock < $item['quantity']) {
                        throw ValidationException::withMessages([
                            'qty' => "Stok {$product->name} tidak mencukupi untuk transaksi ini.",
                        ]);
                    }

                    $product->decrement('stock', $item['quantity']);

                    $purchase->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                if ($member) {
                    if ($pointsUsed > 0) {
                        $member->decrement('points', $pointsUsed);
                    }

                    if ($pointsEarned > 0) {
                        $member->increment('points', $pointsEarned);
                    }
                }
            });
        } catch (ValidationException $exception) {
            return redirect()->route('purchases.create')->withErrors($exception->errors());
        }

        session()->forget('checkout');

        return redirect()->route('purchases.receipt', $purchase)->with('success', 'Pembelian berhasil diproses.');
    }

    public function receipt(Request $request, Purchase $purchase)
    {
        $this->authorizePurchaseAccess($request, $purchase);

        $purchase->load(['items', 'member', 'user']);

        return view('purchases.receipt', [
            'purchase' => $purchase,
            'pdfPreviewUrl' => route('purchases.receipt.pdf', $purchase),
            'pdfDownloadUrl' => route('purchases.receipt.pdf', [$purchase, 'download' => 1]),
        ]);
    }

    public function receiptPdf(Request $request, Purchase $purchase)
    {
        $this->authorizePurchaseAccess($request, $purchase);

        $purchase->load(['items', 'member', 'user']);

        $pdf = Pdf::loadView('purchases.receipt-struk-pdf', [
            'purchase' => $purchase,
        ]);

        $pdf->setPaper([0, 0, 226.77, 841.89], 'portrait');

        $filename = 'struk-' . $purchase->invoice_number . '.pdf';

        if ($request->boolean('download')) {
            return $pdf->download($filename);
        }

        return $pdf->stream($filename);
    }

    public function export(Request $request)
    {
        $query = Purchase::with(['member', 'user'])->latest('purchase_date');
        if (! $request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        }

        $rows = $query->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, [
            'No Invoice',
            'Tanggal',
            'Kasir',
            'Status Member',
            'Nama Member',
            'Poin Digunakan',
            'Poin Didapat',
            'Total Sebelum Diskon',
            'Total Bayar',
            'Uang Dibayar',
            'Kembalian',
        ]);

        foreach ($rows as $purchase) {
            fputcsv($csv, [
                $purchase->invoice_number,
                $purchase->purchase_date->format('d/m/Y H:i'),
                $purchase->user->name,
                $purchase->is_member ? 'Member' : 'Bukan Member',
                $purchase->customer_name,
                $purchase->points_used,
                $purchase->points_earned,
                $purchase->total_before_discount,
                $purchase->total_after_discount,
                $purchase->cash_paid,
                $purchase->change_amount,
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="rekap-pembelian-' . now()->format('Ymd-His') . '.csv"');
    }

    private function authorizePurchaseAccess(Request $request, Purchase $purchase): void
    {
        if ($request->user()->isAdmin()) {
            return;
        }

        abort_if($purchase->user_id !== $request->user()->id, 403, 'Anda tidak memiliki akses ke data ini.');
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $invoice = 'INV-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        } while (Purchase::where('invoice_number', $invoice)->exists());

        return $invoice;
    }

    private function normalizeMoneyInput(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $value);

        return $digits === '' ? null : $digits;
    }
}
