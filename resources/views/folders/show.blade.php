@extends('layouts.app')
@section('title', $folder->name)
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
                        <h2 class="content-header-title float-start mb-0">
                            {{ $folder->name }}
                            @if ($folder->password)
                                <i class="fas fa-lock text-warning ms-2" data-bs-toggle="tooltip" title="Folder dilindungi password"></i>
                            @endif
                        </h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                @if($folder->parent_id)
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('folder.show', $folder->parent_id) }}">Parent Folder</a>
                                    </li>
                                @else
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('folder.index') }}">Data Documents</a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active">{{ $folder->name }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                <div class="mb-1 breadcrumb-right">
                    <div class="dropdown">
                        <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i data-feather="edit"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="{{ route('folder.edit', $folder->id) }}">
                                <i class="me-1" data-feather="edit-2"></i>
                                <span class="align-middle">Edit Folder</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="content-body">
            <section id="dashboard-analytics">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card shadow-sm border-0 p-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <div class="row align-items-center">
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <div class="search-container position-relative">
                                                        <input type="text" id="globalSearch" class="form-control form-control-lg ps-5" 
                                                               placeholder="Cari subfolder atau dokumen..." autocomplete="off">
                                                        <i class="fas fa-search position-absolute search-icon"></i>
                                                        <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute clear-search d-none">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <select id="categoryFilter" class="form-select form-select-lg select2">
                                                        <option value="">Semua Kategori</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4 mb-3 mb-md-0">
                                                    <select id="typeFilter" class="form-select form-select-lg select2">
                                                        <option value="">Semua Tipe</option>
                                                        <option value="folder">Folder Saja</option>
                                                        <option value="document">Dokumen Saja</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    
                                                </div>
                                            </div>
                                            
                                            <!-- Search Results Counter -->
                                            <div id="searchResults" class="mt-3 d-none">
                                                <div class="alert alert-info border-0 bg-light-info">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <span id="searchResultText"></span>
                                                    <button type="button" class="btn btn-sm btn-outline-info ms-2" id="showAll">
                                                        Tampilkan Semua
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex flex-wrap gap-2 mb-3 justify-content-end action-buttons">
                                            <button type="button" class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createSubfolderModal">
                                                <i class="fas fa-folder-plus"></i>
                                                <span class="d-none d-md-inline">Buat Subfolder</span>
                                            </button>
                                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                                <i class="fas fa-upload"></i>
                                                <span class="d-none d-md-inline">Upload</span>
                                            </button>
                                        </div>
                                    </div>
                                    
                                </div>
                                <!-- Search and Filter Section -->

                                

                                <!-- Tombol aksi utama di atas list -->
                                

                                <!-- List View -->
                                <div class="list-group list-group-flush" id="contentList">
                                    @foreach ($folder->children as $subfolder)
                                        <div class="list-group-item content-item folder-item p-0 border-0 mb-2" 
                                             data-name="{{ strtolower($subfolder->name) }}" 
                                             data-category="{{ strtolower($subfolder->category->name ?? '') }}"
                                             data-category-id="{{ $subfolder->category_id }}"
                                             data-type="folder"
                                             data-protected="{{ $subfolder->password ? 'false' : 'true' }}"
                                             data-password="{{ $subfolder->password}}">
                                            <div class="d-flex align-items-center p-3 bg-light rounded position-relative" style="z-index:1;">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="fas fa-folder text-warning fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <h6 class="mb-1 fw-semibold">
                                                                <a href="{{ route('folder.show', $subfolder->id) }}" class="text-decoration-none text-dark">
                                                                    {{ $subfolder->name }}
                                                                </a>
                                                            </h6>
                                                            <div class="d-flex align-items-center gap-3 text-muted small">
                                                                @if($subfolder->category)
                                                                    <span><i class="fas fa-tag me-1"></i>{{ $subfolder->category->name }}</span>
                                                                @endif
                                                                <span><i class="fas fa-folder me-1"></i>{{ $subfolder->children->count() }} subfolder</span>
                                                                <span><i class="fas fa-file me-1"></i>{{ $subfolder->documents->count() }} dokumen</span>
                                                                @if ($subfolder->password)
                                                                    <span class="text-warning"><i class="fas fa-lock me-1"></i>Terproteksi</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Dropdown pindah ke root agar tidak terpotong -->
                                                <div class="dropdown ms-2" style="position: absolute; top: 16px; right: 16px;">
                                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        <li><a class="dropdown-item" href="{{ route('folder.edit', $subfolder->id) }}">
                                                            <i class="fas fa-edit text-primary"></i>Edit
                                                        </a></li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger delete-folder" href="#" 
                                                               data-id="{{ $subfolder->id }}" 
                                                               data-name="{{ $subfolder->name }}"
                                                               data-password="{{ $subfolder->password}}">
                                                            <i class="fas fa-trash"></i>Hapus
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @foreach ($folder->documents as $document)
                                        <div class="list-group-item content-item document-item p-0 border-0 mb-2" 
                                             data-name="{{ strtolower($document->original_filename ?? $document->filename) }}" 
                                             data-category="{{ strtolower($document->category->name ?? '') }}"
                                             data-category-id="{{ $document->category_id }}"
                                             data-type="document"
                                             data-protected="{{ $document->secret_key ? 'true' : 'false' }}"
                                             data-secret="{{ $document->secret_key}}">
                                            <div class="d-flex align-items-center p-3 bg-light rounded position-relative" style="z-index:1;">
                                                <div class="flex-shrink-0 me-3">
                                                    <i class="fas fa-file-alt text-primary fs-4"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <h6 class="mb-1 fw-semibold">{{ $document->original_filename ?? $document->filename }}</h6>
                                                            <div class="d-flex align-items-center gap-3 text-muted small">
                                                                @if($document->category)
                                                                    <span><i class="fas fa-tag me-1"></i>{{ $document->category->name }}</span>
                                                                @endif
                                                                <span><i class="fas fa-calendar me-1"></i>{{ $document->created_at->format('d M Y') }}</span>
                                                                @if($document->secret_key)
                                                                    <span class="text-warning"><i class="fas fa-lock me-1"></i>Terproteksi</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Dropdown pindah ke root agar tidak terpotong -->
                                                <div class="dropdown ms-2" style="position: absolute; top: 16px; right: 16px;">
                                                    <button class="btn btn-sm btn-light rounded-circle shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                        @if ($document->secret_key)
                                                            <li>
                                                                <a href="#" class="dropdown-item unlock-btn" data-id="{{ $document->id }}" data-name="{{ $document->original_filename ?? $document->filename }}">
                                                                    <i class="fas fa-lock text-warning"></i>Unlock & Download
                                                                </a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="{{ route('documents.download', $document->id) }}">
                                                                    <i class="fas fa-download text-success"></i>Download
                                                                </a>
                                                            </li>
                                                        @else    
                                                            <li><a class="dropdown-item" href="{{ route('documents.download', $document->id) }}">
                                                                <i class="fas fa-download text-success"></i>Download
                                                            </a></li>
                                                        @endif
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li><a class="dropdown-item text-danger delete-document" href="#" 
                                                               data-id="{{ $document->id }}" 
                                                               data-name="{{ $document->original_filename ?? $document->filename }}"
                                                               data-secret="{{ $document->secret_key}}">
                                                            <i class="fas fa-trash"></i>Hapus
                                                        </a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- No Results Found -->
                                <div id="noResults" class="text-center py-5 d-none">
                                    <i class="fas fa-search text-muted fa-3x mb-3"></i>
                                    <h6 class="text-muted">Tidak ditemukan hasil untuk pencarian "<span id="searchTerm"></span>"</h6>
                                    <p class="text-muted mb-0">Coba gunakan kata kunci yang berbeda atau periksa ejaan Anda.</p>
                                </div>

                                <!-- Empty State -->
                                @if ($folder->children->count() == 0 && $folder->documents->count() == 0)
                                    <div id="emptyState" class="text-center py-5">
                                        <i class="fas fa-folder-open text-muted fa-3x mb-3"></i>
                                        <h6 class="text-muted">Folder ini masih kosong</h6>
                                        <p class="text-muted mb-4">Mulai dengan membuat subfolder atau mengupload dokumen pertama Anda.</p>
                                        <div class="d-flex justify-content-center gap-3">
                                            <button type="button" class="btn btn-success d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createSubfolderModal">
                                                <i class="fas fa-folder-plus"></i>
                                                <span>Buat Subfolder</span>
                                            </button>
                                            <button type="button" class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
                                                <i class="fas fa-upload"></i>
                                                <span>Upload Dokumen</span>
                                            </button>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

<!-- Unlock Document Modal -->
<div class="modal fade" id="unlockModal" tabindex="-1" aria-labelledby="unlockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('documents.decrypt') }}" id="unlockForm">
            @csrf
            <input type="hidden" name="document_id" id="modal-document-id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="unlockModalLabel">
                        <i class="fas fa-lock me-2"></i>Masukkan Password
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="secret_key" class="form-label">Password Dokumen</label>
                        <input type="password" name="secret_key" class="form-control" id="secret_key" required>
                        <div class="form-text">Masukkan password untuk membuka dan mengunduh dokumen.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-unlock me-2"></i>Unlock & Download
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Protected Item Modal -->
<div class="modal fade" id="deleteProtectedModal" tabindex="-1" aria-labelledby="deleteProtectedModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProtectedModalLabel">
                    <i class="fas fa-shield-alt me-2 text-warning"></i>Konfirmasi Penghapusan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning border-0 bg-light-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Item ini dilindungi password!</strong> Untuk menghapus item ini, Anda harus memasukkan password terlebih dahulu.
                </div>
                <div id="deleteError" class="text-danger small mb-2" style="display:none"></div>
                <div class="mb-3">
                    <label for="deletePassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="deletePassword" placeholder="Masukkan password untuk konfirmasi">
                    <div class="form-text">Password ini diperlukan untuk keamanan penghapusan item terproteksi.</div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="confirmDelete">
                        <label class="form-check-label" for="confirmDelete">
                            Saya yakin ingin menghapus item ini secara permanen
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn" disabled>
                    <i class="fas fa-trash me-2"></i>Hapus Permanen
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Create Subfolder Modal -->
<div class="modal fade" id="createSubfolderModal" tabindex="-1" aria-labelledby="createSubfolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-semibold" id="createSubfolderModalLabel">
                    <i class="fas fa-folder-plus me-2 text-success"></i>Buat Subfolder
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('folder.store') }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                    
                    <div class="mb-3">
                        <label for="subfolderName" class="form-label fw-semibold">Nama Subfolder</label>
                        <input id="subfolderName" class="form-control form-control-lg" name="name" placeholder="Contoh: Dokumen Penting" required>
                    </div>

                    <div class="mb-3">
                        <label for="subfolderCategory" class="form-label fw-semibold">Kategori</label>
                        <select id="subfolderCategory" class="form-select form-select-lg select2" name="category_id">
                            <option value="">-- Pilih Kategori (opsional) --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="subfolderPassword" class="form-label fw-semibold">Password (opsional)</label>
                        <input id="subfolderPassword" class="form-control form-control-lg" type="password" name="password" placeholder="Bisa dikosongkan">
                        <div class="form-text">Password akan melindungi akses ke folder ini.</div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100">
                        <i class="fas fa-folder-plus me-2"></i>Buat Subfolder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-light">
                <h5 class="modal-title fw-semibold" id="uploadDocumentModalLabel">
                    <i class="fas fa-upload me-2 text-primary"></i>Upload Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                    
                    <div class="mb-3">
                        <label for="document" class="form-label fw-semibold">Pilih File</label>
                        <input id="document" class="form-control form-control-lg" type="file" name="pdf" required>
                        <div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</div>
                    </div>

                    <div class="mb-3">
                        <label for="documentCategory" class="form-label fw-semibold">Kategori</label>
                        <select name="category_id" id="documentCategory" class="form-select form-select-lg select2">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                            <span class="help-block text-danger">{{ $errors->first('category_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="documentPassword" class="form-label fw-semibold">Password (opsional)</label>
                        <input type="text" name="secret_key" class="form-control form-control-lg" placeholder="Password (Optional)" id="documentPassword">
                        <div class="form-text">Password akan mengenkripsi dokumen untuk keamanan tambahan.</div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-upload me-2"></i>Upload Dokumen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

<style>
/* Enhanced Styling */
.search-container .search-icon {
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 5;
}

.search-container .clear-search {
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    z-index: 5;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.content-card {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0 !important;
}

.content-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.content-card .content-menu {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.content-card:hover .content-menu {
    opacity: 1;
}

.bg-light-primary { background-color: #e7f3ff !important; }
.bg-light-success { background-color: #e8f5e8 !important; }
.bg-light-danger { background-color: #ffeaea !important; }
.bg-light-warning { background-color: #fff3cd !important; }
.bg-light-info { background-color: #e3f2fd !important; }
.bg-light-secondary { background-color: #f8f9fa !important; }

.modal-content {
    border-radius: 15px;
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

.form-control-lg, .form-select-lg {
    padding: 12px 16px;
    font-size: 1rem;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
}

/* Search Highlight */
.search-highlight {
    background-color: #fff3cd;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 600;
}

/* Loading Animation */
.loading-search {
    position: relative;
}

.loading-search::after {
    content: '';
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translateY(-50%) rotate(0deg); }
    100% { transform: translateY(-50%) rotate(360deg); }
}

/* Custom Badges */
.badge {
    font-size: 0.75rem;
    padding: 6px 10px;
}

/* Enhanced Dropdown */
.dropdown-menu {
    border-radius: 10px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    padding: 10px 0;
}

.dropdown-item {
    padding: 10px 20px;
    transition: all 0.2s ease;
}

.dropdown-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

/* List View Styling */
.list-group-item {
    border: none !important;
    margin-bottom: 0.5rem;
    border-radius: 10px !important;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.list-group-item .bg-light {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.list-group-item .dropdown {
    position: relative;
    z-index: 2;
}

.list-group-item .dropdown-menu {
    z-index: 2000 !important;
    min-width: 160px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    border-radius: 10px;
    position: absolute;
    top: 100%;
    left: auto;
    right: 0;
    will-change: top, left;
}

.dropdown.show .dropdown-menu {
    display: block;
}

.dropdown-toggle:focus {
    outline: none !important;
    box-shadow: none !important;
}

.list-group-item .dropdown-toggle {
    outline: none !important;
    box-shadow: none !important;
}

.list-group-item .dropdown-menu.show {
    display: block;
}

.list-group-item .dropdown-item {
    cursor: pointer;
}

.list-group-item .dropdown-item:active,
.list-group-item .dropdown-item:focus {
    background-color: #f8f9fa;
    color: #212529;
}

.list-group-item .dropdown-divider {
    margin: 0.25rem 0;
}

.list-group-item .d-flex {
    align-items: center;
    gap: 1rem;
}

.list-group-item .flex-grow-1 {
    min-width: 0;
}

.list-group-item .action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.list-group-item .action-buttons .btn {
    min-width: 36px;
    padding: 0.375rem 0.75rem;
}

.list-group-item .action-buttons .btn i {
    margin-right: 0;
}

.list-group-item .dropdown-menu .dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.list-group-item .dropdown-menu .dropdown-item i {
    margin-right: 0.5rem;
}

.list-group-item .dropdown-menu .dropdown-divider {
    margin: 0.25rem 0;
}

.list-group-item .dropdown-menu {
    overflow: visible !important;
}

.list-group-item .dropdown-menu .dropdown-item {
    white-space: nowrap;
}

.list-group-item .dropdown-menu .dropdown-item:active {
    background-color: #f8f9fa;
    color: #212529;
}

.list-group-item .dropdown-menu .dropdown-item:focus {
    background-color: #f8f9fa;
    color: #212529;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .list-group-item .d-flex {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }
    .dropdown {
        align-self: flex-end;
        margin-top: 0.5rem;
    }
    .list-group-item .flex-grow-1 {
        width: 100%;
    }
    .list-group-item .action-buttons {
        gap: 0.25rem;
    }
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('success'))
            swal({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if ($errors->any())
            swal({
                icon: 'error',
                title: 'Terjadi Kesalahan!',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif
    });
</script>
<script>
$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#globalSearch');
    const clearButton = $('#clearSearch');
    const searchResults = $('#searchResults');
    const searchResultText = $('#searchResultText');
    const noResults = $('#noResults');
    const searchTerm = $('#searchTerm');
    const categoryFilter = $('#categoryFilter');
    const typeFilter = $('#typeFilter');
    const contentList = $('#contentList');
    const emptyState = $('#emptyState');

    if ($.fn.select2) {
        $('.select2').select2({
            width: '100%',
            placeholder: function(){
                return $(this).attr('placeholder') || '';
            },
            allowClear: true
        });
    }
    
    // Global Search Functionality
    searchInput.on('input', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val().toLowerCase().trim();
        
        // Add loading class
        $(this).addClass('loading-search');
        
        searchTimeout = setTimeout(function() {
            performSearch(query);
            searchInput.removeClass('loading-search');
        }, 300);
        
        // Show/hide clear button
        if (query.length > 0) {
            clearButton.removeClass('d-none');
        } else {
            clearButton.addClass('d-none');
        }
    });
    
    // Category Filter
    categoryFilter.on('change', function() {
        performSearch(searchInput.val().toLowerCase().trim());
    });
    
    // Type Filter
    typeFilter.on('change', function() {
        performSearch(searchInput.val().toLowerCase().trim());
    });
    
    // Clear search
    clearButton.on('click', function() {
        searchInput.val('').trigger('input').focus();
        categoryFilter.val('');
        typeFilter.val('');
    });
    
    // Show all results
    $('#showAll').on('click', function() {
        searchInput.val('').trigger('input');
        categoryFilter.val('');
        typeFilter.val('');
    });
    
    function performSearch(query) {
        const selectedCategory = categoryFilter.val();
        const selectedType = typeFilter.val();
        
        let visibleItems = 0;
        
        $('.content-item').each(function() {
            const item = $(this);
            const itemName = item.data('name');
            const itemCategory = item.data('category');
            const itemCategoryId = item.data('category-id');
            const itemType = item.data('type');
            const itemProtected = item.data('protected');
            
            let shouldShow = true;
            
            // Text search
            if (query && !itemName.includes(query) && !itemCategory.includes(query)) {
                shouldShow = false;
            }
            
            // Category filter
            if (selectedCategory && itemCategoryId != selectedCategory) {
                shouldShow = false;
            }
            
            // Type filter
            if (selectedType) {
                if (selectedType === 'folder' && itemType !== 'folder') {
                    shouldShow = false;
                } else if (selectedType === 'document' && itemType !== 'document') {
                    shouldShow = false;
                } else if (selectedType === 'protected' && itemProtected !== 'true') {
                    shouldShow = false;
                }
            }
            
            if (shouldShow) {
                item.show().addClass('search-result');
                visibleItems++;
                if (query) {
                    highlightText(item, query);
                }
            } else {
                item.hide().removeClass('search-result');
            }
        });
        
        // Update UI based on results
        updateSearchResults(visibleItems, query, selectedCategory, selectedType);
    }
    
    function updateSearchResults(visibleItems, query, category, type) {
        const hasFilters = query || category || type;
        
        if (visibleItems === 0 && hasFilters) {
            searchResults.addClass('d-none');
            noResults.removeClass('d-none');
            searchTerm.text(query || 'filter yang dipilih');
            contentList.hide();
            emptyState.hide();
        } else if (visibleItems === 0 && !hasFilters) {
            searchResults.addClass('d-none');
            noResults.addClass('d-none');
            contentList.hide();
            emptyState.show();
        } else {
            noResults.addClass('d-none');
            emptyState.hide();
            contentList.show();
            
            if (hasFilters) {
                searchResults.removeClass('d-none');
                
                let resultText = `Ditemukan ${visibleItems} hasil`;
                if (query) resultText += ` untuk "${query}"`;
                if (category) resultText += ` dalam kategori yang dipilih`;
                if (type) resultText += ` dengan tipe yang dipilih`;
                
                searchResultText.text(resultText);
            } else {
                searchResults.addClass('d-none');
            }
        }
    }
    
    function highlightText(element, query) {
        // Remove existing highlights
        element.find('.search-highlight').contents().unwrap();
        
        // Highlight matching text
        element.find('*').addBack().contents().filter(function() {
            return this.nodeType === 3; // Text nodes only
        }).each(function() {
            const text = this.textContent;
            const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
            if (regex.test(text)) {
                const highlightedText = text.replace(regex, '<span class="search-highlight">$1</span>');
                $(this).replaceWith(highlightedText);
            }
        });
    }
    
    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }
    
    // Handle folder deletion
    $('.delete-folder').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var hasPassword = $(this).data('password');
        console.log(hasPassword, name, id);
        
        if (hasPassword) {
            $('#deleteProtectedModal').modal('show');
            $('#deleteProtectedModal').data('item-id', id);
            $('#deleteProtectedModal').data('item-name', name);
            $('#deleteProtectedModal').data('item-type', 'folder');
            $('#deletePassword').val('');
            $('#confirmDelete').prop('checked', false);
            $('#confirmDeleteBtn').prop('disabled', true);
        } else {
            // Regular deletion for unprotected folders
            swal({
                title: 'Apakah Anda yakin?',
                text: `Subfolder "${name}" akan dihapus permanen!`,
                type: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya, Hapus!',
                        className: 'btn btn-danger'
                    },
                    cancel: {
                        visible: true,
                        text: 'Batal',
                        className: 'btn btn-secondary'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    deleteFolder(id, name);
                }
            });
        }
    });

    // Handle document deletion
    $('.delete-document').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var name = $(this).data('name');
        var hasSecret = $(this).data('secret');
        
        if (hasSecret) {
            $('#deleteProtectedModal').modal('show');
            $('#deleteProtectedModal').data('item-id', id);
            $('#deleteProtectedModal').data('item-name', name);
            $('#deleteProtectedModal').data('item-type', 'document');
            $('#deletePassword').val('');
            $('#confirmDelete').prop('checked', false);
            $('#confirmDeleteBtn').prop('disabled', true);
        } else {
            // Regular deletion for unprotected documents
            swal({
                title: 'Apakah Anda yakin?',
                text: `Dokumen "${name}" akan dihapus permanen!`,
                type: 'warning',
                buttons: {
                    confirm: {
                        text: 'Ya, Hapus!',
                        className: 'btn btn-danger'
                    },
                    cancel: {
                        visible: true,
                        text: 'Batal',
                        className: 'btn btn-secondary'
                    }
                }
            }).then((Delete) => {
                if (Delete) {
                    deleteDocument(id, name);
                }
            });
        }
    });

    // Handle unlock document
    $('.unlock-btn').click(function(e) {
        e.preventDefault();
        const documentId = this.getAttribute('data-id');
        const documentName = this.getAttribute('data-name');
        document.getElementById('modal-document-id').value = documentId;
        
        // Update modal title with document name
        $('#unlockModalLabel').html(`<i class="fas fa-lock me-2"></i>Masukkan Password - ${documentName}`);
        
        var modal = new bootstrap.Modal(document.getElementById('unlockModal'));
        modal.show();
    });

    function checkEmptyState() {
        if ($('.content-item:visible').length === 0) {
            contentList.hide();
            emptyState.show();
        }
    }
    
    // Helper functions for deletion
    function deleteFolder(id, name) {
        $.ajax({
            url: `{{ url('folder') }}/` + id,
            method: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    swal("Berhasil!", response.message, {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then(() => {
                        // Animate removal
                        $(`[data-id="${id}"]`).closest('.list-group-item').fadeOut(400, function() {
                            $(this).remove();
                            checkEmptyState();
                        });
                    });
                } else {
                    swal("Error!", response.message, {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan saat menghapus subfolder.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                swal("Error!", errorMessage, {
                    icon: "error",
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }
                    },
                });
            }
        });
    }
    
    function deleteDocument(id, name) {
        $.ajax({
            url: `{{ url('documents') }}/` + id,
            method: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    swal("Berhasil!", response.message, {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then(() => {
                        // Animate removal
                        $(`[data-id="${id}"]`).closest('.list-group-item').fadeOut(400, function() {
                            $(this).remove();
                            checkEmptyState();
                        });
                    });
                } else {
                    swal("Error!", response.message, {
                        icon: "error",
                        buttons: {
                            confirm: {
                                className: 'btn btn-danger'
                            }
                        },
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = 'Terjadi kesalahan saat menghapus dokumen.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                swal("Error!", errorMessage, {
                    icon: "error",
                    buttons: {
                        confirm: {
                            className: 'btn btn-danger'
                        }
                    },
                });
            }
        });
    }
    
    // Protected item deletion modal handlers
    $('#deleteProtectedModal').on('show.bs.modal', function() {
        $('#deleteError').hide().text('');
        $('#deletePassword').val('');
        $('#confirmDelete').prop('checked', false);
        $('#confirmDeleteBtn').prop('disabled', true);
    });

    $('#deletePassword').on('input', function() {
        var password = $(this).val();
        var isChecked = $('#confirmDelete').is(':checked');
        $('#confirmDeleteBtn').prop('disabled', password.length === 0 || !isChecked);
        $('#deleteError').hide().text('');
    });

    $('#confirmDelete').on('change', function() {
        var password = $('#deletePassword').val();
        var isChecked = $(this).is(':checked');
        $('#confirmDeleteBtn').prop('disabled', password.length === 0 || !isChecked);
        $('#deleteError').hide().text('');
    });

    $('#confirmDeleteBtn').off('click').on('click', function() {
        var itemId = $('#deleteProtectedModal').data('item-id');
        var itemName = $('#deleteProtectedModal').data('item-name');
        var itemType = $('#deleteProtectedModal').data('item-type');
        var password = $('#deletePassword').val();
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');
        $('#deleteError').hide().text('');
        if (itemType === 'folder') {
            deleteProtectedFolder(itemId, itemName, password, $btn);
        } else if (itemType === 'document') {
            deleteProtectedDocument(itemId, itemName, password, $btn);
        }
    });
    
    function deleteProtectedFolder(id, name, password, $btn) {
        $.ajax({
            url: `{{ url('folder') }}/` + id,
            method: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}",
                "password": password
            },
            dataType: 'json',
            success: function(response) {
                $('#deleteProtectedModal').modal('hide');
                $btn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Hapus Permanen');
                if (response.success) {
                    swal("Berhasil!", response.message, {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then(() => {
                        // Langsung reload halaman setelah sukses
                        location.reload();
                    });
                } else {
                    // Show error in modal
                    $('#deleteProtectedModal').modal('show');
                    $('#deleteError').show().text(response.message || 'Password salah atau terjadi kesalahan.');
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Hapus Permanen');
                var errorMessage = 'Password salah atau terjadi kesalahan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $('#deleteError').show().text(errorMessage);
            }
        });
    }
    
    function deleteProtectedDocument(id, name, password, $btn) {
        $.ajax({
            url: `{{ url('documents') }}/` + id,
            method: 'DELETE',
            data: {
                "_token": "{{ csrf_token() }}",
                "password": password
            },
            dataType: 'json',
            success: function(response) {
                $('#deleteProtectedModal').modal('hide');
                $btn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Hapus Permanen');
                if (response.success) {
                    swal("Berhasil!", response.message, {
                        icon: "success",
                        buttons: {
                            confirm: {
                                className: 'btn btn-success'
                            }
                        },
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Show error in modal
                    $('#deleteProtectedModal').modal('show');
                    $('#deleteError').show().text(response.message || 'Password salah atau terjadi kesalahan.');
                }
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Hapus Permanen');
                var errorMessage = 'Password salah atau terjadi kesalahan.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }
                $('#deleteError').show().text(errorMessage);
            }
        });
    }

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Enhanced form validation and UX
    $('#createSubfolderModal form').on('submit', function() {
        $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin me-2"></i>Membuat Subfolder...');
    });
    
    $('#uploadDocumentModal form').on('submit', function() {
        $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin me-2"></i>Mengupload...');
    });
    
    // File input enhancement
    $('#document').on('change', function() {
        const fileName = $(this)[0].files[0]?.name;
        if (fileName) {
            $(this).next('.form-text').html(`<i class="fas fa-file me-1 text-success"></i>File dipilih: <strong>${fileName}</strong>`);
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + K for search focus
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && searchInput.is(':focus')) {
            clearButton.click();
        }
    });
    
    // Add keyboard shortcut hint
    searchInput.attr('placeholder', 'Cari subfolder atau dokumen... (Ctrl+K)');
    
    // Auto-focus search on page load if there are many items
    const totalItems = $('.content-item').length;
    if (totalItems > 10) {
        setTimeout(() => {
            if (!searchInput.is(':focus')) {
                searchInput.focus();
            }
        }, 1000);
    }
    
    // Smooth scroll to search results
    searchInput.on('focus', function() {
        $('html, body').animate({
            scrollTop: $(this).offset().top - 100
        }, 300);
    });
    
    // Enhanced drag and drop for file upload
    const uploadModal = $('#uploadDocumentModal');
    const fileInput = $('#document');
    
    uploadModal.on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('drag-over');
    });
    
    uploadModal.on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
    });
    
    uploadModal.on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('drag-over');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            fileInput[0].files = files;
            fileInput.trigger('change');
        }
    });
    
    // Add success animation for alerts
    $('.alert-success').each(function() {
        $(this).hide().fadeIn(500);
        setTimeout(() => {
            $(this).fadeOut(500);
        }, 5000);
    });
});

// Dropdown menu fix: position fixed saat open agar tidak tertutup
$(document).on('show.bs.dropdown', '.dropdown', function () {
    var $menu = $(this).find('.dropdown-menu');
    var offset = $menu.offset();
    var height = $menu.outerHeight();
    var winHeight = $(window).height();
    // Jika dropdown akan keluar dari viewport bawah, geser ke atas
    if (offset && offset.top + height > winHeight) {
        $menu.css({ top: 'auto', bottom: '100%' });
    } else {
        $menu.css({ top: '', bottom: '' });
    }
    $menu.css('z-index', 2000);
});
$(document).on('hide.bs.dropdown', '.dropdown', function () {
    $(this).find('.dropdown-menu').css({ top: '', bottom: '' });
});
</script>
@endpush