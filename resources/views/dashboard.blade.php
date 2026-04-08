@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="panel-card p-3">
                <p class="muted mb-1">Total Produk</p>
                <h3 class="mb-0">{{ $stats['product_count'] }}</h3>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="panel-card p-3">
                <p class="muted mb-1">Stok Menipis (&lt;5)</p>
                <h3 class="mb-0">{{ $stats['low_stock_count'] }}</h3>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="panel-card p-3">
                <p class="muted mb-1">Transaksi Hari Ini</p>
                <h3 class="mb-0">{{ $stats['today_count'] }}</h3>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="panel-card p-3">
                <p class="muted mb-1">Omzet Hari Ini</p>
                <h3 class="mb-0">Rp {{ number_format($stats['today_income'], 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <div class="panel-card p-3 p-lg-4 mb-4">
        <h5 class="mb-3">Transaksi Terbaru</h5>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>No Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Member</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($latestPurchases as $purchase)
                        <tr>
                            <td>{{ $purchase->invoice_number }}</td>
                            <td>{{ $purchase->purchase_date->format('d/m/Y H:i') }}</td>
                            <td>{{ $purchase->user->name }}</td>
                            <td>{{ $purchase->is_member ? $purchase->customer_name ?? '-' : 'Bukan Member' }}</td>
                            <td>Rp {{ number_format($purchase->total_after_discount, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
