@extends('layouts.app')
@section('title', 'Access Protected Folder')
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        {{-- <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Protected Folder</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    @if($folder->parent_id)
                                        <a href="{{ route('folder.show', $folder->parent_id) }}">Parent Folder</a>
                                    @else
                                        <a href="{{ route('folder.index') }}">Data Documents</a>
                                    @endif
                                </li>
                                <li class="breadcrumb-item active">{{ $folder->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        
        <div class="content-body">
            <section class="d-flex justify-content-center align-items-center" style="min-height: 60vh;">
                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-lg">
                        <div class="card-body text-center p-4">
                            <div class="mb-4">
                                <i class="fas fa-lock text-warning" style="font-size: 4rem;"></i>
                            </div>
                            
                            <h4 class="card-title mb-3">Folder Protected</h4>
                            <p class="text-muted mb-4">
                                Folder "<strong>{{ $folder->name }}</strong>" dilindungi dengan password.
                                Masukkan password untuk mengakses.
                            </p>
                            
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}
                                    @endforeach
                                </div>
                            @endif

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ route('folder.access', $folder->id) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Password</label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Masukkan password folder" 
                                           required 
                                           autofocus>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-unlock me-2"></i>Akses Folder
                                    </button>
                                    <a href="{{ $folder->parent_id ? route('folder.show', $folder->parent_id) : route('folder.index') }}" 
                                       class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection