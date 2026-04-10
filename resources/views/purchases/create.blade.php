@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@section('content')
    <form method="POST" action="{{ route('purchases.prepare') }}" class="panel-card p-3 p-lg-4">
        @csrf

        @error('qty')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <p class="muted">Pilih produk dan atur jumlah dengan tombol plus/minus. Jumlah tidak boleh melebihi stok.</p>

        <div class="row g-3">
            @forelse($products as $product)
                <div class="col-md-6 col-xl-4">
                    <div class="card h-100" style="border-radius:14px; border:1px solid #e7d8c8;">
                        @if ($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 170px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mb-1">{{ $product->name }}</h5>
                            <div class="muted small mb-2">Stok tersedia: <strong>{{ $product->stock }}</strong></div>
                            <div class="fw-bold mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</div>

                            <div class="input-group mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-minus"
                                    data-target="qty{{ $product->id }}">-</button>
                                <input type="number" min="0" max="{{ $product->stock }}"
                                    data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                                    class="form-control qty-input" id="qty{{ $product->id }}"
                                    name="qty[{{ $product->id }}]" value="{{ old('qty.' . $product->id, 0) }}">
                                <button type="button" class="btn btn-outline-secondary btn-plus"
                                    data-target="qty{{ $product->id }}">+</button>
                            </div>

                            <div class="small">Subtotal: <span class="subtotal" data-for="qty{{ $product->id }}">Rp
                                    0</span></div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">Belum ada produk tersedia.</div>
            @endforelse
        </div>

        <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-4">
            <h4 class="mb-0">Total: <span id="grandTotal">Rp 0</span></h4>
            <div class="d-flex gap-2">
                <a href="{{ route('purchases.index') }}" class="btn btn-outline-dark">Kembali</a>
                <button class="btn btn-dark">Selanjutnya</button>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        const formatRupiah = (num) => 'Rp ' + Number(num).toLocaleString('id-ID');

        const qtyInputs = document.querySelectorAll('.qty-input');
        const minusButtons = document.querySelectorAll('.btn-minus');
        const plusButtons = document.querySelectorAll('.btn-plus');
        const grandTotal = document.getElementById('grandTotal');

        const calculate = () => {
            let total = 0;

            qtyInputs.forEach((input) => {
                const price = Number(input.dataset.price);
                const stock = Number(input.dataset.stock);
                let qty = Number(input.value || 0);

                if (qty < 0) qty = 0;
                if (qty > stock) qty = stock;
                input.value = qty;

                const subtotal = qty * price;
                total += subtotal;

                const subtotalEl = document.querySelector(`.subtotal[data-for="${input.id}"]`);
                if (subtotalEl) {
                    subtotalEl.textContent = formatRupiah(subtotal);
                }
            });

            grandTotal.textContent = formatRupiah(total);
        };

        minusButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                input.value = Math.max(Number(input.value || 0) - 1, 0);
                calculate();
            });
        });

        plusButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const input = document.getElementById(btn.dataset.target);
                const stock = Number(input.dataset.stock);
                input.value = Math.min(Number(input.value || 0) + 1, stock);
                calculate();
            });
        });

        qtyInputs.forEach((input) => {
            input.addEventListener('input', calculate);
        });

        calculate();
    </script>
@endpush
