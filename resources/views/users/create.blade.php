@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <div class="panel-card p-3 p-lg-4">
        <form method="POST" action="{{ route('users.store') }}" class="row g-3 needs-validation" novalidate>
            @csrf
            <div class="col-md-6">
                <label class="form-label">Nama <span class="text-danger">*</span></label>
                <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                    required>
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Nama harus diisi!</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Email harus diisi!</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Telepon</label>
                <input name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}"
                    placeholder="08xxxxxxxxxx">
                @error('phone')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Role <span class="text-danger">*</span></label>
                @if (auth()->user()?->isAdmin())
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih role</option>
                        <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                        <option value="petugas" @selected(old('role') === 'petugas')>Petugas</option>
                        <option value="member" @selected(old('role') === 'member')>Member</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @else
                        <div class="invalid-feedback">Role harus dipilih!</div>
                    @enderror
                @else
                    <select class="form-select" disabled>
                        <option value="member" selected>Member</option>
                    </select>
                    <input type="hidden" name="role" value="member">
                    <small class="text-muted">Petugas hanya bisa membuat akun member.</small>
                @endif
            </div>
            <div class="col-md-6">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    required>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Password harus diisi!</div>
                @enderror
            </div>

            <div class="col-12 d-flex gap-2">
                <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Kembali</a>
                <button class="btn btn-dark">Simpan</button>
            </div>
        </form>
    </div>
@endsection
