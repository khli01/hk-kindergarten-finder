@extends('layouts.admin')

@section('title', 'Edit Kindergarten')
@section('page-title', 'Edit Kindergarten')

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.kindergartens.update', $kindergarten) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Names -->
            <h5 class="mb-3">Basic Information</h5>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Name (Traditional Chinese) *</label>
                    <input type="text" name="name_zh_tw" class="form-control @error('name_zh_tw') is-invalid @enderror" 
                           value="{{ old('name_zh_tw', $kindergarten->name_zh_tw) }}" required>
                    @error('name_zh_tw')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Name (Simplified Chinese) *</label>
                    <input type="text" name="name_zh_cn" class="form-control @error('name_zh_cn') is-invalid @enderror" 
                           value="{{ old('name_zh_cn', $kindergarten->name_zh_cn) }}" required>
                    @error('name_zh_cn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Name (English) *</label>
                    <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" 
                           value="{{ old('name_en', $kindergarten->name_en) }}" required>
                    @error('name_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <!-- District and Type -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">District *</label>
                    <select name="district_id" class="form-select @error('district_id') is-invalid @enderror" required>
                        @foreach($districts->groupBy('region') as $region => $regionDistricts)
                            <optgroup label="{{ ucfirst(str_replace('_', ' ', $region)) }}">
                                @foreach($regionDistricts as $district)
                                    <option value="{{ $district->id }}" {{ old('district_id', $kindergarten->district_id) == $district->id ? 'selected' : '' }}>
                                        {{ $district->name_en }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">School Type</label>
                    <select name="school_type" class="form-select">
                        <option value="private" {{ old('school_type', $kindergarten->school_type) == 'private' ? 'selected' : '' }}>Private</option>
                        <option value="non_profit" {{ old('school_type', $kindergarten->school_type) == 'non_profit' ? 'selected' : '' }}>Non-Profit</option>
                        <option value="government" {{ old('school_type', $kindergarten->school_type) == 'government' ? 'selected' : '' }}>Government</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Established Year</label>
                    <input type="number" name="established_year" class="form-control" 
                           value="{{ old('established_year', $kindergarten->established_year) }}" min="1900" max="{{ date('Y') }}">
                </div>
            </div>

            <!-- Address -->
            <h5 class="mb-3">Address</h5>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Address (Traditional Chinese) *</label>
                    <input type="text" name="address_zh_tw" class="form-control" 
                           value="{{ old('address_zh_tw', $kindergarten->address_zh_tw) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Address (Simplified Chinese) *</label>
                    <input type="text" name="address_zh_cn" class="form-control" 
                           value="{{ old('address_zh_cn', $kindergarten->address_zh_cn) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Address (English) *</label>
                    <input type="text" name="address_en" class="form-control" 
                           value="{{ old('address_en', $kindergarten->address_en) }}" required>
                </div>
            </div>

            <!-- Contact -->
            <h5 class="mb-3">Contact Information</h5>
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $kindergarten->phone) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $kindergarten->email) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Website URL</label>
                    <input type="url" name="website_url" class="form-control" value="{{ old('website_url', $kindergarten->website_url) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Principal Name</label>
                    <input type="text" name="principal_name" class="form-control" value="{{ old('principal_name', $kindergarten->principal_name) }}">
                </div>
            </div>

            <!-- Classes and Ranking -->
            <h5 class="mb-3">Classes & Ranking</h5>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label">Available Classes</label>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input type="checkbox" name="has_pn_class" value="1" class="form-check-input" 
                                   {{ old('has_pn_class', $kindergarten->has_pn_class) ? 'checked' : '' }}>
                            <label class="form-check-label">PN Class</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="has_k1" value="1" class="form-check-input" 
                                   {{ old('has_k1', $kindergarten->has_k1) ? 'checked' : '' }}>
                            <label class="form-check-label">K1</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="has_k2" value="1" class="form-check-input" 
                                   {{ old('has_k2', $kindergarten->has_k2) ? 'checked' : '' }}>
                            <label class="form-check-label">K2</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="has_k3" value="1" class="form-check-input" 
                                   {{ old('has_k3', $kindergarten->has_k3) ? 'checked' : '' }}>
                            <label class="form-check-label">K3</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Ranking Score (0-100)</label>
                    <input type="number" name="ranking_score" class="form-control" 
                           value="{{ old('ranking_score', $kindergarten->ranking_score) }}" min="0" max="100">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Primary Success Rate (%)</label>
                    <input type="number" name="primary_success_rate" class="form-control" 
                           value="{{ old('primary_success_rate', $kindergarten->primary_success_rate) }}" min="0" max="100" step="0.1">
                </div>
            </div>

            <!-- Fees and Status -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label class="form-label">Monthly Fee (Min)</label>
                    <input type="number" name="monthly_fee_min" class="form-control" 
                           value="{{ old('monthly_fee_min', $kindergarten->monthly_fee_min) }}" min="0">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Monthly Fee (Max)</label>
                    <input type="number" name="monthly_fee_max" class="form-control" 
                           value="{{ old('monthly_fee_max', $kindergarten->monthly_fee_max) }}" min="0">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <div class="form-check">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" 
                               {{ old('is_active', $kindergarten->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label">Active (visible on website)</label>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <h5 class="mb-3">Description</h5>
            <div class="row mb-4">
                <div class="col-md-4">
                    <label class="form-label">Description (Traditional Chinese)</label>
                    <textarea name="description_zh_tw" class="form-control" rows="4">{{ old('description_zh_tw', $kindergarten->description_zh_tw) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description (Simplified Chinese)</label>
                    <textarea name="description_zh_cn" class="form-control" rows="4">{{ old('description_zh_cn', $kindergarten->description_zh_cn) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Description (English)</label>
                    <textarea name="description_en" class="form-control" rows="4">{{ old('description_en', $kindergarten->description_en) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Update Kindergarten
                </button>
                <a href="{{ route('admin.kindergartens.show', $kindergarten) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
