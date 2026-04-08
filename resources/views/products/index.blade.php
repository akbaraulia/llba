@extends('layouts.app')

@section('title', 'Produk')
@section('page_title', 'Data Produk')

@section('content')
<div class="panel-card p-3 p-lg-4">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <form class="d-flex gap-2" method="GET" action="{{ route('products.index') }}">
            <input class="form-control" type="text" name="q" value="{{ $search }}" placeholder="Cari nama produk...">
            <button class="btn btn-outline-dark">Cari</button>
        </form>

        @if(auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-dark">Tambah Produk</a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama</th>
                <th>Harga</th>
                <th>Stok</th>
                @if(auth()->user()->isAdmin())
                    <th>Aksi</th>
                @endif
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <div class="card" style="width: 120px; border-radius: 12px; overflow:hidden;">
                            @if($product->image_path)
                                <img src="{{ route('media.show', ['path' => $product->image_path]) }}" alt="{{ $product->name }}" style="height: 80px; object-fit: cover;">
                            @else
                                <div class="p-2 text-muted small">Tanpa gambar</div>
                            @endif
                        </div>
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td>{{ $product->stock }}</td>
                    @if(auth()->user()->isAdmin())
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#stockModal{{ $product->id }}">Update Stok</button>
                                <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">Hapus</button>
                            </div>
                        </td>
                    @endif
                </tr>

                @if(auth()->user()->isAdmin())
                    <div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Stok {{ $product->name }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="{{ route('products.stock.update', $product) }}">
                                    @csrf
                                    @method('PATCH')
                                    <div class="modal-body">
                                        <label class="form-label">Stok Baru</label>
                                        <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control">

                                        @if(session('open_stock_modal') == $product->id && $errors->stock->any())
                                            <div class="text-danger small mt-2">{{ $errors->stock->first('stock') }}</div>
                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-warning">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">Hapus produk {{ $product->name }}?</div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <form method="POST" action="{{ route('products.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada data produk.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>
@endsection

@push('scripts')
@if(session('open_stock_modal'))
<script>
    (() => {
        const targetId = {{ session('open_stock_modal') }};
        const modalEl = document.getElementById(`stockModal${targetId}`);
        if (modalEl) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
    })();
</script>
@endif
@endpush
