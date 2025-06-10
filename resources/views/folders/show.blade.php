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
                            <div class="card shadow-sm border-0 p-3">
                                
                                <div class="mb-3">
                                    <div class="d-flex flex-wrap gap-2 mb-3">
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

                                @if (session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
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
                                    <div class="mb-4">
                                        <h5 class="mb-3">
                                            <i class="fas fa-folder me-2"></i>Subfolder
                                        </h5>
                                        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4 mb-4">
                                            @foreach ($folder->children as $subfolder)
                                                <div class="col">
                                                    <div class="card h-100 folder-card border shadow-sm hover-shadow position-relative">
                                                        <!-- Dropdown Menu -->
                                                        <div class="dropdown position-absolute" style="top: 10px; right: 10px; z-index: 10;">
                                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ route('folder.edit', $subfolder->id) }}">
                                                                    <i class="fas fa-edit me-2"></i>Edit
                                                                </a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger delete-folder" href="#" data-id="{{ $subfolder->id }}">
                                                                    <i class="fas fa-trash me-2"></i>Hapus
                                                                </a></li>
                                                            </ul>
                                                        </div>

                                                        <a href="{{ route('folder.show', $subfolder->id) }}" class="text-decoration-none">
                                                            <div class="card-body text-center p-4">
                                                                <div class="folder-icon mb-3">
                                                                    <i class="fas fa-folder text-warning" style="font-size: 2.5rem;"></i>
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                                    <h6 class="card-title mb-0 text-dark fw-medium text-truncate">
                                                                        {{ $subfolder->name }}
                                                                    </h6>
                                                                    @if ($subfolder->password)
                                                                        <i class="fas fa-lock text-secondary" data-bs-toggle="tooltip" title="Folder dilindungi password"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Documents Section -->
                                <div>
                                    <h5 class="mb-3">
                                        <i class="fas fa-file me-2"></i>Dokumen
                                    </h5>
                                    @if ($folder->documents->count() > 0)
                                        <div class="table-bordered">
                                            <table class="table table-hover">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Nama File</th>
                                                        <th>Kategori</th>
                                                        <th>Tanggal Upload</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($folder->documents as $document)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-file-alt text-primary me-2"></i>
                                                                    {{ $document->original_filename ?? $document->filename }}
                                                                </div>
                                                            </td>
                                                            <td>
                                                                {{ $document->category->name ?? '-' }}
                                                            </td>
                                                            <td>{{ $document->created_at->format('d-m-Y') }}</td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                        Aksi
                                                                    </button>
                                                                    <ul class="dropdown-menu">
                                                                        <li><a class="dropdown-item" href="{{ route('documents.download', $document->id) }}">
                                                                            <i class="fas fa-download me-2"></i>Download
                                                                        </a></li>
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
                                        <div class="alert alert-light text-center py-5">
                                            <i class="fas fa-file-alt text-muted me-2 fa-2x"></i>
                                            <h6 class="mt-3">Belum ada dokumen dalam folder ini.</h6>
                                        </div>
                                    @endif
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

<!-- Create Subfolder Modal -->
<div class="modal fade" id="createSubfolderModal" tabindex="-1" aria-labelledby="createSubfolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="createSubfolderModalLabel">Buat Subfolder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('folder.store') }}">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                    
                    <div class="mb-3">
                        <label for="subfolderName" class="form-label fw-semibold">Nama Subfolder</label>
                        <input id="subfolderName" class="form-control" name="name" placeholder="Contoh: Dokumen Penting" required>
                    </div>

                    <div class="mb-3">
                        <label for="subfolderCategory" class="form-label fw-semibold">Kategori</label>
                        <select id="subfolderCategory" class="form-select" name="category_id">
                            <option value="">-- Pilih Kategori (opsional) --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subfolderPassword" class="form-label fw-semibold">Password (opsional)</label>
                        <input id="subfolderPassword" class="form-control" type="password" name="password" placeholder="Bisa dikosongkan">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-folder-plus me-1"></i> Buat Subfolder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="uploadDocumentModalLabel">Upload Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="folder_id" value="{{ $folder->id }}">
                    
                    <div class="mb-3">
                        <label for="document" class="form-label fw-semibold">Pilih File</label>
                        <input id="document" class="form-control" type="file" name="pdf" required>
                        <div class="form-text">Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX</div>
                    </div>

                    <div class="mb-3">
                        <label for="documentName" class="form-label fw-semibold">Kategori</label>
                        <select name="category_id" id="" class="form-select">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('category_id'))
                                <span class="help-block" style="color:red">{{ $errors->first('category_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold">Password (opsional)</label>
                        {{ Form::text('secret_key', null, ['class' => 'form-control', 'placeholder' => 'Password (Optional)']) }}
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Upload Dokumen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<style>
.hover-shadow:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

.folder-card {
    transition: all 0.3s ease;
}
</style>

<script>
$(document).ready(function() {
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
                        });
                        location.reload();
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
        
        swal({
            title: 'Apakah Anda yakin?',
            text: "Dokumen akan dihapus permanen!",
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
                        });
                        location.reload();
                    },
                    error: function(xhr) {
                        swal("Error!", "Terjadi kesalahan saat menghapus dokumen.", {
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
});
</script>
@endpush