@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Export Documents</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('documents.do-export') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Filter by Category (Optional)</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(auth()->user()->isAdmin())
                        <div class="mb-3">
                            <label for="user_id" class="form-label">Filter by User (Optional, Admin only)</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">All Users</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label for="export_filename" class="form-label">Export Filename (Optional)</label>
                            <input type="text" class="form-control" id="export_filename" name="export_filename" 
                                   placeholder="documents-export.xlsx">
                            <div class="form-text">If not provided, a default filename will be used.</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-export"></i> Export Documents
                            </button>
                            <a href="{{ route('documents.export-all') }}" class="btn btn-secondary">
                                <i class="fas fa-download"></i> Quick Export All
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection