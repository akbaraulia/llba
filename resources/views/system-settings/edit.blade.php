@extends('layouts.app')

@section('title', 'System Settings')
@section('page_title', 'System Settings')

@section('content')
    <div class="panel-card p-3 p-lg-4">
        <form method="POST" action="{{ route('system-settings.update') }}" class="row g-3 needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="col-md-4">
                <label class="form-label">1 Poin = Berapa Rupiah <span class="text-danger">*</span></label>
                <input type="number" min="0.01" step="0.01" name="point_redeem_value"
                    class="form-control @error('point_redeem_value') is-invalid @enderror"
                    value="{{ old('point_redeem_value', $settings->point_redeem_value) }}" required>
                @error('point_redeem_value')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Nilai poin harus diisi!</div>
                @enderror
                <small class="text-muted">Contoh 1 berarti 1 poin bernilai Rp 1.</small>
            </div>

            <div class="col-md-4">
                <label class="form-label">1 Poin Didapat per Rupiah <span class="text-danger">*</span></label>
                <input type="number" min="0.01" step="0.01" name="point_earn_spend"
                    class="form-control @error('point_earn_spend') is-invalid @enderror"
                    value="{{ old('point_earn_spend', $settings->point_earn_spend) }}" required>
                @error('point_earn_spend')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Rate perolehan poin harus diisi!</div>
                @enderror
                <small class="text-muted">Contoh 10000 berarti 1 poin per belanja Rp 10.000.</small>
            </div>

            <div class="col-md-4">
                <label class="form-label">Default Maks Pemakaian Poin (%) <span class="text-danger">*</span></label>
                <input type="number" min="0" max="100" step="0.01" name="default_max_redeem_percentage"
                    class="form-control @error('default_max_redeem_percentage') is-invalid @enderror"
                    value="{{ old('default_max_redeem_percentage', $settings->default_max_redeem_percentage) }}" required>
                @error('default_max_redeem_percentage')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Batas maksimal redeem harus diisi!</div>
                @enderror
                <small class="text-muted">Default harus 10%, bisa diubah saat dibutuhkan.</small>
            </div>

            <div class="col-12 d-flex gap-2">
                <button class="btn btn-dark">Simpan Settings</button>
            </div>
        </form>
    </div>
@endsection
