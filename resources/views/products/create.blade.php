@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('page_title', 'Tambah Produk')

@section('content')
    <div class="panel-card p-3 p-lg-4">
        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data"
            class="row g-3 needs-validation" novalidate>
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nama Produk <span class="text-danger">*</span></label>
                <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                    required>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Nama produk harus diisi!</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Harga <span class="text-danger">*</span></label>
                <input name="price" id="price" class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price') }}" required>
                @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Harga harus diisi!</div>
                @enderror
                <small class="text-muted">Format otomatis rupiah.</small>
            </div>
            <div class="col-md-6">
                <label class="form-label">Stok <span class="text-danger">*</span></label>
                <input type="number" min="0" name="stock"
                    class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" required>
                @error('stock')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Stok harus diisi!</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Gambar Produk <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror"
                    accept="image/*" required>
                @error('image')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Gambar produk harus dipilih!</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <a class="btn btn-outline-dark" href="{{ route('products.index') }}">Kembali</a>
                <button class="btn btn-dark">Simpan</button>
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
