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
                            <i data-feather="grid"></i>
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
                                
                                <!-- Search and Action Buttons Section -->
                                <div class="mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-3 mb-md-0">
                                            <div class="search-container position-relative">
                                                <input type="text" id="globalSearch" class="form-control form-control-lg ps-5" 
                                                       placeholder="Cari subfolder atau dokumen..." autocomplete="off">
                                                <i class="fas fa-search position-absolute search-icon"></i>
                                                <button type="button" id="clearSearch" class="btn btn-sm btn-light position-absolute clear-search d-none">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
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

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show border-0 bg-light-success" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show border-0 bg-light-danger" role="alert">
                                        <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Error!</h6>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <!-- Subfolders Section -->
                                @if ($folder->children->count() > 0)
                                    <div class="mb-5" id="subfoldersSection">
                                        <div class="d-flex align-items-center mb-4">
                                            <h5 class="mb-0 me-3">
                                                <i class="fas fa-folder me-2 text-warning"></i>Subfolder
                                            </h5>
                                            <span class="badge bg-light-warning text-warning rounded-pill" id="subfolderCount">
                                                {{ $folder->children->count() }}
                                            </span>
                                        </div>
                                        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4" id="subfoldersGrid">
                                            @foreach ($folder->children as $subfolder)
                                                <div class="col folder-item" data-name="{{ strtolower($subfolder->name) }}" data-category="{{ strtolower($subfolder->category->name ?? '') }}">
                                                    <div class="card h-100 folder-card border-0 shadow-sm position-relative">
                                                        <!-- Dropdown Menu -->
                                                        <div class="dropdown position-absolute folder-menu" style="top: 15px; right: 15px; z-index: 10;">
                                                            <button class="btn btn-sm btn-light rounded-circle shadow-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                                                <li><a class="dropdown-item" href="{{ route('folder.edit', $subfolder->id) }}">
                                                                    <i class="fas fa-edit me-2 text-primary"></i>Edit
                                                                </a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger delete-folder" href="#" data-id="{{ $subfolder->id }}">
                                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                                </a></li>
                                                            </ul>
                                                        </div>

                                                        <a href="{{ route('folder.show', $subfolder->id) }}" class="text-decoration-none h-100">
                                                            <div class="card-body text-center p-4 d-flex flex-column justify-content-center h-100">
                                                                <div class="folder-icon mb-3">
                                                                    <i class="fas fa-folder text-warning" style="font-size: 3rem;"></i>
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                                                    <h6 class="card-title mb-0 text-dark fw-semibold text-truncate" style="max-width: 150px;">
                                                                        {{ $subfolder->name }}
                                                                    </h6>
                                                                    @if ($subfolder->password)
                                                                        <i class="fas fa-lock text-muted" data-bs-toggle="tooltip" title="Folder dilindungi password"></i>
                                                                    @endif
                                                                </div>
                                                                @if($subfolder->category)
                                                                    <small class="text-muted mt-2">{{ $subfolder->category->name }}</small>
                                                                @endif
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Documents Section -->
                                <div id="documentsSection">
                                    <div class="d-flex align-items-center mb-4">
                                        <h5 class="mb-0 me-3">
                                            <i class="fas fa-file-alt me-2 text-primary"></i>Dokumen
                                        </h5>
                                        <span class="badge bg-light-primary text-primary rounded-pill" id="documentCount">
                                            {{ $folder->documents->count() }}
                                        </span>
                                    </div>
                                    @if ($folder->documents->count() > 0)
                                        <div class="table-responsive shadow-sm rounded">
                                            <table class="table table-hover mb-0" id="documentsTable">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="border-0 fw-semibold">Nama File</th>
                                                        <th class="border-0 fw-semibold">Kategori</th>
                                                        <th class="border-0 fw-semibold">Tanggal Upload</th>
                                                        <th class="border-0 fw-semibold">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($folder->documents as $document)
                                                        <tr class="document-row" data-name="{{ strtolower($document->original_filename ?? $document->filename) }}" data-category="{{ strtolower($document->category->name ?? '') }}">
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="file-icon me-3">
                                                                        <i class="fas fa-file-alt text-primary fs-4"></i>
                                                                    </div>
                                                                    <div>
                                                                        <div class="fw-medium text-dark">{{ $document->original_filename ?? $document->filename }}</div>
                                                                        @if($document->secret_key)
                                                                            <small class="text-muted">
                                                                                <i class="fas fa-lock me-1"></i>Dokumen terproteksi
                                                                            </small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if($document->category)
                                                                    <span class="badge bg-light-secondary text-secondary">
                                                                        {{ $document->category->name }}
                                                                    </span>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <small class="text-muted">{{ $document->created_at->format('d M Y') }}</small>
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle border-0" type="button" data-bs-toggle="dropdown">
                                                                        <i class="fas fa-cog me-1"></i>Aksi
                                                                    </button>
                                                                    <ul class="dropdown-menu shadow border-0">
                                                                        @if ($document->secret_key)
                                                                            <li>
                                                                                <a href="#" class="dropdown-item unlock-btn" data-id="{{ $document->id }}">
                                                                                    <i class="fas fa-lock me-2"></i>Unlock & Download
                                                                                </a>
                                                                            </li>
                                                                        @else    
                                                                            <li><a class="dropdown-item" href="{{ route('documents.download', $document->id) }}">
                                                                                <i class="fas fa-download me-2 text-success"></i>Download
                                                                            </a></li>
                                                                        @endif
                                                                        <li><hr class="dropdown-divider"></li>
                                                                        <li><a class="dropdown-item text-danger delete-document" href="#" data-id="{{ $document->id }}">
                                                                            <i class="fas fa-trash me-2"></i>Hapus
                                                                        </a></li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="alert alert-light text-center py-5 border-0 bg-light">
                                            <i class="fas fa-file-alt text-muted fa-3x mb-3"></i>
                                            <h6 class="text-muted">Belum ada dokumen dalam folder ini.</h6>
                                            <p class="text-muted mb-0">Klik tombol "Upload Dokumen" untuk menambahkan dokumen pertama Anda.</p>
                                        </div>
                                    @endif
                                </div>

                                <!-- No Results Found -->
                                <div id="noResults" class="text-center py-5 d-none">
                                    <i class="fas fa-search text-muted fa-3x mb-3"></i>
                                    <h6 class="text-muted">Tidak ditemukan hasil untuk pencarian "<span id="searchTerm"></span>"</h6>
                                    <p class="text-muted mb-0">Coba gunakan kata kunci yang berbeda atau periksa ejaan Anda.</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END: Content-->

<div class="modal fade" id="unlockModal" tabindex="-1" aria-labelledby="unlockModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('documents.decrypt') }}" id="unlockForm">
        @csrf
        <input type="hidden" name="document_id" id="modal-document-id">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="unlockModalLabel"><i class="fas fa-lock me-2"></i>Masukkan Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
              <div class="mb-3">
                  <label for="secret_key" class="form-label">Password Dokumen</label>
                  <input type="password" name="secret_key" class="form-control" id="secret_key" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Unlock & Download</button>
          </div>
        </div>
    </form>
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
                        <select id="subfolderCategory" class="form-select form-select-lg" name="category_id">
                            <option value="">-- Pilih Kategori (opsional) --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="subfolderPassword" class="form-label fw-semibold">Password (opsional)</label>
                        <input id="subfolderPassword" class="form-control form-control-lg" type="password" name="password" placeholder="Bisa dikosongkan">
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
                        <select name="category_id" id="documentCategory" class="form-select form-select-lg">
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
                        {{ Form::text('secret_key', null, ['class' => 'form-control form-control-lg', 'placeholder' => 'Password (Optional)', 'id' => 'documentPassword']) }}
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

.folder-card {
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0 !important;
}

.folder-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.folder-card .folder-menu {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.folder-card:hover .folder-menu {
    opacity: 1;
}

.document-row {
    transition: all 0.2s ease;
}

.document-row:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.file-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #e7f3ff;
    border-radius: 8px;
}

.bg-light-primary { background-color: #e7f3ff !important; }
.bg-light-success { background-color: #e8f5e8 !important; }
.bg-light-danger { background-color: #ffeaea !important; }
.bg-light-warning { background-color: #fff3cd !important; }
.bg-light-info { background-color: #e3f2fd !important; }
.bg-light-secondary { background-color: #f8f9fa !important; }

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #495057;
    padding: 15px;
}

.table td {
    padding: 15px;
    vertical-align: middle;
}

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
</style>

<script>
$(document).ready(function() {
    let searchTimeout;
    const searchInput = $('#globalSearch');
    const clearButton = $('#clearSearch');
    const searchResults = $('#searchResults');
    const searchResultText = $('#searchResultText');
    const noResults = $('#noResults');
    const searchTerm = $('#searchTerm');
    
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
    
    // Clear search
    clearButton.on('click', function() {
        searchInput.val('').trigger('input').focus();
    });
    
    // Show all results
    $('#showAll').on('click', function() {
        searchInput.val('').trigger('input');
    });
    
    function performSearch(query) {
        if (query === '') {
            showAllItems();
            return;
        }
        
        let folderResults = 0;
        let documentResults = 0;
        
        // Search in subfolders
        $('.folder-item').each(function() {
            const folderName = $(this).data('name');
            const folderCategory = $(this).data('category');
            
            if (folderName.includes(query) || folderCategory.includes(query)) {
                $(this).show().addClass('search-result');
                folderResults++;
                highlightText($(this), query);
            } else {
                $(this).hide().removeClass('search-result');
            }
        });
        
        // Search in documents
        $('.document-row').each(function() {
            const documentName = $(this).data('name');
            const documentCategory = $(this).data('category');
            
            if (documentName.includes(query) || documentCategory.includes(query)) {
                $(this).show().addClass('search-result');
                documentResults++;
                highlightText($(this), query);
            } else {
                $(this).hide().removeClass('search-result');
            }
        });
        
        // Update counters
        updateSearchResults(folderResults, documentResults, query);
    }
    
    function showAllItems() {
        $('.folder-item, .document-row').show().removeClass('search-result');
        $('.search-highlight').contents().unwrap();
        searchResults.addClass('d-none');
        noResults.addClass('d-none');
        updateCounters();
    }
    
    function updateSearchResults(folderResults, documentResults, query) {
        const totalResults = folderResults + documentResults;
        
        if (totalResults === 0) {
            searchResults.addClass('d-none');
            noResults.removeClass('d-none');
            searchTerm.text(query);
        } else {
            noResults.addClass('d-none');
            searchResults.removeClass('d-none');
            
            let resultText = `Ditemukan ${totalResults} hasil untuk "${query}"`;
            if (folderResults > 0 && documentResults > 0) {
                resultText += ` (${folderResults} subfolder, ${documentResults} dokumen)`;
            } else if (folderResults > 0) {
                resultText += ` (${folderResults} subfolder)`;
            } else {
                resultText += ` (${documentResults} dokumen)`;
            }
            
            searchResultText.text(resultText);
        }
        
        // Update section visibility
        if (folderResults === 0) {
            $('#subfoldersSection').hide();
        } else {
            $('#subfoldersSection').show();
        }
        
        if (documentResults === 0) {
            $('#documentsSection').hide();
        } else {
            $('#documentsSection').show();
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
    
    function updateCounters() {
        const visibleFolders = $('.folder-item:visible').length;
        const visibleDocuments = $('.document-row:visible').length;
        
        $('#subfolderCount').text(visibleFolders);
        $('#documentCount').text(visibleDocuments);
    }
    
    // Handle folder deletion
    $('.delete-folder').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var folderName = $(this).closest('.folder-card').find('.card-title').text().trim();
        
        swal({
            title: 'Apakah Anda yakin?',
            text: `Subfolder "${folderName}" akan dihapus permanen!`,
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
                // Show loading state
                $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...');
                
                $.ajax({
                    url: `{{ url('folder') }}/` + id,
                    method: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        swal("Berhasil!", "Subfolder berhasil dihapus!", {
                            icon: "success",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            },
                        }).then(() => {
                            // Animate removal
                            $(`[data-id="${id}"]`).closest('.col').fadeOut(400, function() {
                                $(this).remove();
                                updateCounters();
                            });
                        });
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
        });
    });

    // Handle document deletion
    $('.delete-document').click(function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var documentName = $(this).closest('tr').find('td:first .fw-medium').text().trim();
        
        swal({
            title: 'Apakah Anda yakin?',
            text: `Dokumen "${documentName}" akan dihapus permanen!`,
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
                // Show loading state
                $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Menghapus...');
                
                $.ajax({
                    url: `{{ url('documents') }}/` + id,
                    method: 'DELETE',
                    data: {
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(data) {
                        swal("Berhasil!", "Dokumen berhasil dihapus!", {
                            icon: "success",
                            buttons: {
                                confirm: {
                                    className: 'btn btn-success'
                                }
                            },
                        }).then(() => {
                            // Animate removal
                            $(`[data-id="${id}"]`).closest('tr').fadeOut(400, function() {
                                $(this).remove();
                                updateCounters();
                                
                                // Check if table is empty
                                if ($('#documentsTable tbody tr:visible').length === 0) {
                                    $('#documentsTable').closest('.table-responsive').fadeOut(400, function() {
                                        $(this).replaceWith(`
                                            <div class="alert alert-light text-center py-5 border-0 bg-light">
                                                <i class="fas fa-file-alt text-muted fa-3x mb-3"></i>
                                                <h6 class="text-muted">Belum ada dokumen dalam folder ini.</h6>
                                                <p class="text-muted mb-0">Klik tombol "Upload Dokumen" untuk menambahkan dokumen pertama Anda.</p>
                                            </div>
                                        `);
                                    });
                                }
                            });
                        });
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
        });
    });

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
    const totalItems = $('.folder-item').length + $('.document-row').length;
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
    
    // Enhanced drag and drop for file upload (if needed in future)
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
    
    // Initialize counters on page load
    updateCounters();
    
    // Add success animation for alerts
    $('.alert-success').each(function() {
        $(this).hide().fadeIn(500);
        setTimeout(() => {
            $(this).fadeOut(500);
        }, 5000);
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.unlock-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const documentId = this.getAttribute('data-id');
                document.getElementById('modal-document-id').value = documentId;
                var modal = new bootstrap.Modal(document.getElementById('unlockModal'));
                modal.show();
            });
        });
    });
</script>
@endpush