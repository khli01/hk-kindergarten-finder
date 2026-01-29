@extends('layouts.admin')

@section('title', 'Kindergartens')
@section('page-title', 'Kindergartens Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="text-muted">{{ $kindergartens->total() }} kindergarten(s) found</span>
    </div>
    <a href="{{ route('admin.kindergartens.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Add Kindergarten
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.kindergartens.index') }}" method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="district" class="form-select">
                    <option value="">All Districts</option>
                    @foreach($districts as $district)
                        <option value="{{ $district->id }}" {{ request('district') == $district->id ? 'selected' : '' }}>
                            {{ $district->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="active" class="form-select">
                    <option value="">All Status</option>
                    <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">Filter</button>
                <a href="{{ route('admin.kindergartens.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Kindergartens Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>District</th>
                    <th>Classes</th>
                    <th>Ranking</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kindergartens as $k)
                    <tr>
                        <td>{{ $k->id }}</td>
                        <td>
                            <a href="{{ route('admin.kindergartens.show', $k) }}" class="text-decoration-none">
                                <strong>{{ $k->name_en }}</strong>
                            </a>
                            <br>
                            <small class="text-muted">{{ $k->name_zh_tw }}</small>
                        </td>
                        <td>{{ $k->district->name_en }}</td>
                        <td>
                            @if($k->has_pn_class)<span class="badge bg-info">PN</span>@endif
                            @if($k->has_k1)<span class="badge bg-primary">K1</span>@endif
                            @if($k->has_k2)<span class="badge bg-primary">K2</span>@endif
                            @if($k->has_k3)<span class="badge bg-primary">K3</span>@endif
                        </td>
                        <td>
                            @if($k->ranking_score)
                                <span class="badge bg-warning text-dark">{{ $k->ranking_score }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $k->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $k->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.kindergartens.show', $k) }}" class="btn btn-outline-primary" title="View">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.kindergartens.edit', $k) }}" class="btn btn-outline-secondary" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.kindergartens.destroy', $k) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure?')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No kindergartens found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $kindergartens->links() }}
</div>
@endsection
