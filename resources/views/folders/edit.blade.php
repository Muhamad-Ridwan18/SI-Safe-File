@extends('layouts.app')
@section('title', 'Edit Folder')
@section('content')

<!-- BEGIN: Content-->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Edit Folder</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    @if($folder->parent_id)
                                        <a href="{{ route('folder.show', $folder->parent_id) }}">Parent Folder</a>
                                    @else
                                        <a href="{{ route('folder.index') }}">Data Documents</a>
                                    @endif
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('folder.show', $folder->id) }}">{{ $folder->name }}</a>
                                </li>
                                <li class="breadcrumb-item active">Edit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            <section id="basic-form-layouts">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Edit Folder: {{ $folder->name }}</h4>
                            </div>
                            <div class="card-body">
                                
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <h4>Error!</h4>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('folder.update', $folder->id) }}">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="folderName" class="form-label fw-semibold">Nama Folder <span class="text-danger">*</span></label>
                                                <input id="folderName" 
                                                       class="form-control @error('name') is-invalid @enderror" 
                                                       name="name" 
                                                       value="{{ old('name', $folder->name) }}"
                                                       placeholder="Contoh: Dokumen Siswa" 
                                                       required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="category" class="form-label fw-semibold">Kategori</label>
                                                <select id="category" 
                                                        class="form-select select2 @error('category_id') is-invalid @enderror" 
                                                        name="category_id">
                                                    <option value="">-- Pilih Kategori (opsional) --</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" 
                                                                {{ old('category_id', $folder->category_id) == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold">Password Protection</label>
                                                
                                                @if($folder->password)
                                                    <div class="alert alert-info mb-2">
                                                        <i class="fas fa-info-circle me-2"></i>
                                                        Folder ini saat ini dilindungi password.
                                                    </div>
                                                    
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" 
                                                               type="checkbox" 
                                                               id="removePassword" 
                                                               name="remove_password" 
                                                               value="1">
                                                        <label class="form-check-label" for="removePassword">
                                                            Hapus password protection
                                                        </label>
                                                    </div>
                                                @endif
                                                
                                                <input id="folderPassword" 
                                                       class="form-control @error('password') is-invalid @enderror" 
                                                       type="password" 
                                                       name="password" 
                                                       placeholder="{{ $folder->password ? 'Masukkan password baru (kosongkan jika tidak ingin mengubah)' : 'Masukkan password (opsional)' }}">
                                                
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                
                                                <div class="form-text">
                                                    @if($folder->password)
                                                        Kosongkan jika tidak ingin mengubah password. Centang checkbox di atas untuk menghapus password sepenuhnya.
                                                    @else
                                                        Kosongkan jika tidak ingin melindungi folder dengan password.
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save me-1"></i> Update Folder
                                                </button>
                                                <a href="{{ route('folder.show', $folder->id) }}" class="btn btn-secondary">
                                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

@endsection

@push('scripts')

<script>
$(document).ready(function() {
    if ($.fn.select2) {
        $('.select2').select2({
            width: '100%',
            placeholder: function(){
                return $(this).attr('placeholder') || '';
            },
            allowClear: true
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    const removePasswordCheckbox = document.getElementById('removePassword');
    const passwordInput = document.getElementById('folderPassword');
    
    if (removePasswordCheckbox) {
        removePasswordCheckbox.addEventListener('change', function() {
            if (this.checked) {
                passwordInput.disabled = true;
                passwordInput.value = '';
                passwordInput.placeholder = 'Password akan dihapus';
            } else {
                passwordInput.disabled = false;
                passwordInput.placeholder = 'Masukkan password baru (kosongkan jika tidak ingin mengubah)';
            }
        });
    }
});
</script>
@endpush