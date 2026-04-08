@extends('layouts.app')

@section('title', 'Pembelian')
@section('page_title', 'Data Pembelian')

@section('content')
<div class="panel-card p-3 p-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <div class="d-flex gap-2">
            @if(! auth()->user()->isAdmin())
                <a href="{{ route('purchases.create') }}" class="btn btn-dark">Tambah Pembelian</a>
            @endif
            <a href="{{ route('purchases.export') }}" class="btn btn-outline-success">Export Rekap</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Member</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
            </thead>
            <tbody>
            @forelse($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->invoice_number }}</td>
                    <td>{{ $purchase->purchase_date->format('d/m/Y H:i') }}</td>
                    <td>{{ $purchase->user->name }}</td>
                    <td>{{ $purchase->is_member ? ($purchase->customer_name ?? '-') : 'Bukan Member' }}</td>
                    <td>
                        @if($purchase->points_used > 0)
                            <div><s>Rp {{ number_format($purchase->total_before_discount, 0, ',', '.') }}</s></div>
                            <div class="text-success fw-bold">Rp {{ number_format($purchase->total_after_discount, 0, ',', '.') }}</div>
                        @else
                            Rp {{ number_format($purchase->total_after_discount, 0, ',', '.') }}
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $purchase->id }}">Lihat</button>
                            <a href="{{ route('purchases.receipt', $purchase) }}" class="btn btn-sm btn-outline-dark">Struk</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Belum ada data pembelian.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    @foreach($purchases as $purchase)
        <div class="modal fade" id="detailModal{{ $purchase->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail {{ $purchase->invoice_number }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Qty</th>
                                    <th>Harga</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($purchase->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-6">
                                <p class="mb-1">Status: <strong>{{ $purchase->is_member ? 'Member' : 'Bukan Member' }}</strong></p>
                                <p class="mb-1">Poin Digunakan: <strong>{{ $purchase->points_used }}</strong></p>
                                <p class="mb-1">Poin Didapat: <strong>{{ $purchase->points_earned }}</strong></p>
                                <p class="mb-1">Diskon Poin: <strong>Rp {{ number_format($purchase->points_discount_amount, 0, ',', '.') }}</strong></p>
                                <p class="mb-0">Max Poin Dipakai: <strong>{{ number_format($purchase->max_redeem_percentage, 2, ',', '.') }}%</strong></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1">Total Awal: <strong>Rp {{ number_format($purchase->total_before_discount, 0, ',', '.') }}</strong></p>
                                <p class="mb-1">Total Bayar: <strong>Rp {{ number_format($purchase->total_after_discount, 0, ',', '.') }}</strong></p>
                                <p class="mb-1">Dibayar: <strong>Rp {{ number_format($purchase->cash_paid, 0, ',', '.') }}</strong></p>
                                <p class="mb-0">Kembalian: <strong>Rp {{ number_format($purchase->change_amount, 0, ',', '.') }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    {{ $purchases->links() }}
</div>
@endsection
