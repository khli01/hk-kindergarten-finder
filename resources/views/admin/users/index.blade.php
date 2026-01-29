@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'User Management')

@section('content')
<!-- Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['total'] }}</h3>
                <small>Total Users</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h3>{{ $stats['verified'] }}</h3>
                <small>Verified</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h3>{{ $stats['admins'] }}</h3>
                <small>Admins</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.users.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="verified" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('verified') === '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="admin" class="form-select">
                    <option value="">All Roles</option>
                    <option value="1" {{ request('admin') === '1' ? 'selected' : '' }}>Admins</option>
                    <option value="0" {{ request('admin') === '0' ? 'selected' : '' }}>Regular Users</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Language</th>
                    <th>Favorites</th>
                    <th>Suggestions</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user) }}" class="text-decoration-none">
                                {{ $user->name }}
                            </a>
                            @if($user->is_admin)
                                <span class="badge bg-danger">Admin</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->preferred_language }}</td>
                        <td>{{ $user->favorites_count }}</td>
                        <td>{{ $user->suggestions_count }}</td>
                        <td>
                            @if($user->email_verified)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning text-dark">Unverified</span>
                            @endif
                        </td>
                        <td><small>{{ $user->created_at->format('Y-m-d') }}</small></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.admin', $user) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('{{ $user->is_admin ? 'Revoke' : 'Grant' }} admin status?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-{{ $user->is_admin ? 'warning' : 'success' }}" 
                                                title="{{ $user->is_admin ? 'Revoke Admin' : 'Make Admin' }}">
                                            <i class="bi bi-{{ $user->is_admin ? 'person-dash' : 'person-plus' }}"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-muted">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $users->links() }}
</div>
@endsection
