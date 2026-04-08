@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Produk')

@section('content')
    <div class="panel-card p-3 p-lg-4">
        <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data"
            class="row g-3 needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="col-md-6">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Nama produk harus diisi!</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga <span class="text-danger">*</span></label>
                <input name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price', number_format((float) $product->price, 0, '', '')) }}" required>
                @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Harga harus diisi!</div>
                @enderror
                <small class="text-muted">Format otomatis rupiah.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Informasi Stok</label>
                <div class="form-control bg-light d-flex align-items-center justify-content-between">
                    <span>Stok saat ini</span>
                    <strong>{{ $product->stock }}</strong>
                </div>
                <small class="text-muted">Stok tidak dapat diubah di halaman ini. Ubah dari tombol Update Stok di daftar
                    produk.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar Produk (opsional)</label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                    accept="image/*">
                @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            @if ($product->image_path)
                <div class="col-md-4">
                    <div class="card" style="border-radius:12px; overflow:hidden;">
                        <img src="{{ route('media.show', ['path' => $product->image_path]) }}" alt="{{ $product->name }}"
                            style="height:180px; object-fit: cover;">
                    </div>
                </div>
            @endif

            <div class="col-12 d-flex gap-2">
                <a class="btn btn-outline-dark" href="{{ route('products.index') }}">Kembali</a>
                <button class="btn btn-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        const priceInput = document.getElementById('price');

        const normalizePriceValue = (value) => {
            const stringValue = String(value ?? '').trim();
            if (!stringValue) return '';

            // Handle values like 10000.00 coming from decimal database casts.
            if (/^\d+\.\d{1,2}$/.test(stringValue)) {
                return String(Math.round(Number(stringValue)));
            }

            return stringValue.replace(/[^\d]/g, '');
        };

        const formatRupiah = (value) => {
            const clean = normalizePriceValue(value);
            if (!clean) return '';
            return 'Rp ' + Number(clean).toLocaleString('id-ID');
        };

        priceInput.addEventListener('input', (e) => {
            e.target.value = formatRupiah(e.target.value);
        });

        if (priceInput.value) {
            priceInput.value = formatRupiah(priceInput.value);
        }
    </script>
@endpush
