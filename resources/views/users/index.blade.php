@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="panel-card p-3 p-lg-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <form class="d-flex gap-2" method="GET" action="{{ route('users.index') }}">
                <input class="form-control" type="text" name="q" value="{{ $search }}"
                    placeholder="Cari nama/email/telepon...">
                <button class="btn btn-outline-dark">Cari</button>
            </form>

            <a href="{{ route('users.create') }}" class="btn btn-dark">Tambah Pengguna</a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge text-bg-secondary">{{ strtoupper($user->role) }}</span></td>
                                <td>{{ $user->phone ?: '-' }}</td>
                                <td>
                                    @if (auth()->user()?->isAdmin())
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="btn btn-sm btn-outline-primary">Edit</a>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteUserModal{{ $user->id }}">Hapus</button>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>

                            @if (auth()->user()?->isAdmin())
                                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Hapus Pengguna</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">Hapus pengguna {{ $user->name }}?</div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <form method="POST" action="{{ route('users.destroy', $user) }}">
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
                                <td colspan="5" class="text-center text-muted">Belum ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
@endsection
