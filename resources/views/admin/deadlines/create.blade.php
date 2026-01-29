@extends('layouts.admin')

@section('title', 'Add Deadline')
@section('page-title', 'Add Registration Deadline')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.deadlines.store') }}" method="POST">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Kindergarten *</label>
                            <select name="kindergarten_id" class="form-select @error('kindergarten_id') is-invalid @enderror" required>
                                <option value="">Select Kindergarten</option>
                                @foreach($kindergartens as $k)
                                    <option value="{{ $k->id }}" {{ old('kindergarten_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name_en }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kindergarten_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Academic Year *</label>
                            <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" 
                                   value="{{ old('academic_year', date('Y') . '-' . (date('Y') + 1)) }}" placeholder="e.g., 2025-2026" required>
                            @error('academic_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Event Type *</label>
                            <select name="event_type" class="form-select @error('event_type') is-invalid @enderror" required>
                                <option value="application_start" {{ old('event_type') == 'application_start' ? 'selected' : '' }}>Application Start</option>
                                <option value="application_deadline" {{ old('event_type') == 'application_deadline' ? 'selected' : '' }}>Application Deadline</option>
                                <option value="interview" {{ old('event_type') == 'interview' ? 'selected' : '' }}>Interview</option>
                                <option value="result_announcement" {{ old('event_type') == 'result_announcement' ? 'selected' : '' }}>Result Announcement</option>
                                <option value="registration" {{ old('event_type') == 'registration' ? 'selected' : '' }}>Registration</option>
                                <option value="open_day" {{ old('event_type') == 'open_day' ? 'selected' : '' }}>Open Day</option>
                                <option value="briefing_session" {{ old('event_type') == 'briefing_session' ? 'selected' : '' }}>Briefing Session</option>
                                <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('event_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Date *</label>
                            <input type="date" name="deadline_date" class="form-control @error('deadline_date') is-invalid @enderror" 
                                   value="{{ old('deadline_date') }}" required>
                            @error('deadline_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Time</label>
                            <input type="time" name="deadline_time" class="form-control" value="{{ old('deadline_time') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Notes (Traditional Chinese)</label>
                            <textarea name="notes_zh_tw" class="form-control" rows="2">{{ old('notes_zh_tw') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Notes (Simplified Chinese)</label>
                            <textarea name="notes_zh_cn" class="form-control" rows="2">{{ old('notes_zh_cn') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Notes (English)</label>
                            <textarea name="notes_en" class="form-control" rows="2">{{ old('notes_en') }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Source URL</label>
                        <input type="url" name="source_url" class="form-control" value="{{ old('source_url') }}" 
                               placeholder="https://school.edu.hk/admission">
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_verified" value="1" class="form-check-input" {{ old('is_verified') ? 'checked' : '' }}>
                            <label class="form-check-label">Mark as Verified</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Create Deadline
                        </button>
                        <a href="{{ route('admin.deadlines.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
