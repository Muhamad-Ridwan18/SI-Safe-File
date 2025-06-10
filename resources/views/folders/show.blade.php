@extends('layouts.app')

@section('title', 'Folders')

@push('styles')
<style>

    .view-toggle-btn {
        transition: all 0.2s ease;
    }

    .view-toggle-btn.active {
        background-color: var(--primary-color);
        color: white;
    }

    .view-container {
        display: none;
    }

    .view-container.active {
        display: block;
    }

    .grid-view .item {
        margin: 0.5rem;
        text-align: center;
    }

    .list-view .item {
        padding: 0.75rem;
        border-bottom: 1px solid #eee;
        transition: background-color 0.2s ease;
    }

    .list-view .item:hover {
        background-color: #f8f9fa;
    }


    .file-icon, .folder-icon {
        position: relative;
    }

    .badge-lock {
        font-size: 0.6rem;
        padding: 0.2rem 0.4rem;
    }

    @media (max-width: 576px) {

        .nav-pills {
            flex-wrap: nowrap;
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }

        .grid-view .item {
            width: 100%;
        }
    }

    @media (min-width: 768px) {
        .grid-view .item {
            width: calc(50% - 1rem);
        }
    }

    @media (min-width: 992px) {
        .grid-view .item {
            width: calc(33.333% - 1rem);
        }
    }

    @media (min-width: 1200px) {
        .grid-view .item {
            width: calc(25% - 1rem);
        }
    }
</style>
@endpush

@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
          <div class="col-12 d-flex justify-content-between align-items-center flex-wrap">
               <h2 class="content-header-title mb-0">{{ $folder->name }}</h2>
               <div class="d-inline-flex gap-2 mt-2 mt-md-0">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
                         <i class="fas fa-upload me-1"></i> Upload File
                    </button>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                         <i class="fas fa-folder-plus me-1"></i> Create Subfolder
                    </button>
               </div>
          </div>
         </div>

        <div class="content-body">
            <section id="dashboard-analytics">
                <!-- Alerts -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-2 mt-2">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-2 mt-2">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Content Controls -->
                <div class="d-flex justify-content-between align-items-center mb-2 mt-2">
                    <ul class="nav nav-pills" id="folderContentTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-content" 
                                    type="button" role="tab" aria-selected="true">
                                <i class="fas fa-th me-1"></i> All
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="folders-tab" data-bs-toggle="tab" data-bs-target="#folders-content" 
                                    type="button" role="tab" aria-selected="false">
                                <i class="fas fa-folder me-1"></i> Subfolders
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="files-tab" data-bs-toggle="tab" data-bs-target="#files-content" 
                                    type="button" role="tab" aria-selected="false">
                                <i class="fas fa-file me-1"></i> Files
                            </button>
                        </li>
                    </ul>

                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary view-toggle-btn active" id="grid-view-btn">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary view-toggle-btn" id="list-view-btn">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="tab-content" id="folderContentTabsContent">
                    <!-- All Content Tab -->
                    <div class="tab-pane fade show active" id="all-content" role="tabpanel">
                        <div class="view-container list-view">
                            <div class="card">
                                <div class="list-group list-group-flush">
                                    {{-- Subfolders --}}
                                    @foreach ($folder->children as $sub)
                                        <div class="list-group-item item">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="folder-icon me-3 position-relative">
                                                            <i class="fas fa-folder fa-lg text-warning"></i>
                                                            @if ($sub->password)
                                                                <span class="position-absolute translate-middle badge badge-lock bg-secondary">
                                                                    <i class="fas fa-lock"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('folder.show', $sub->id) }}" class="text-decoration-none text-dark text-truncate">
                                                            {{ $sub->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-3 d-none d-md-block text-muted small">
                                                    {{ $sub->created_at->format('d M Y H:i') }}
                                                </div>
                                                <div class="col-3 col-md-2 text-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item edit-folder-btn" href="#" data-id="{{ $sub->id }}" data-name="{{ $sub->name }}" data-action="{{ route('folder.update', $sub->id) }}"><i class="fas fa-pen me-2"></i>Edit</a></li>
                                                            <li><a class="dropdown-item delete-btn" href="#" data-url="{{ route('folder.destroy', $sub->id) }}"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    {{-- Files --}}
                                    @foreach ($folder->documents as $file)
                                        <div class="list-group-item item">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="file-icon me-3 position-relative">
                                                            @php
                                                                $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
                                                                $iconClass = 'fa-file-alt';
                                                                $iconColor = 'text-info';
                                                                $fileIcons = [
                                                                    ['ext' => ['jpg', 'jpeg', 'png', 'gif', 'svg'], 'icon' => 'fa-file-image', 'color' => 'text-success'],
                                                                    ['ext' => ['doc', 'docx'], 'icon' => 'fa-file-word', 'color' => 'text-primary'],
                                                                    ['ext' => ['xls', 'xlsx'], 'icon' => 'fa-file-excel', 'color' => 'text-success'],
                                                                    ['ext' => ['pdf'], 'icon' => 'fa-file-pdf', 'color' => 'text-danger'],
                                                                    ['ext' => ['zip', 'rar', '7z'], 'icon' => 'fa-file-archive', 'color' => 'text-warning']
                                                                ];
                                                                foreach ($fileIcons as $icon) {
                                                                    if (in_array($extension, $icon['ext'])) {
                                                                        $iconClass = $icon['icon'];
                                                                        $iconColor = $icon['color'];
                                                                        break;
                                                                    }
                                                                }
                                                            @endphp
                                                            <i class="fas {{ $iconClass }} fa-lg {{ $iconColor }}"></i>
                                                            @if ($file->password)
                                                                <span class="position-absolute translate-middle badge badge-lock bg-secondary">
                                                                    <i class="fas fa-lock"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="text-dark text-truncate">{{ $file->original_filename }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-3 d-none d-md-block text-muted small">
                                                    {{ $file->created_at->format('d M Y H:i') }}
                                                </div>
                                                <div class="col-3 col-md-2 text-end">
                                                    @if ($file->secret_key)
                                                    <button type="button"
                                                        class="btn btn-link btn-warning btn-sm decrypt"
                                                        data-id="{{ $file->id }}">
                                                        <i data-feather='unlock'></i>
                                                    </button>
                                                         <div class="modal fade" id="decryptModal" tabindex="-1" role="dialog" aria-labelledby="decryptModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="decryptModalLabel">Decrypt Document</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"  aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <form id="decryptForm">
                                                                            @csrf
                                                                            <input type="hidden" name="document_id" id="document_id" value="{{ $file->id }}">
                                                                            <div class="form-group">
                                                                                <label for="secret_key">Secret Key</label>
                                                                                <input type="text" class="form-control" id="secret_key" name="secret_key" required>
                                                                            </div>
                                                                            <div class="form-group mt-2">
                                                                                <button type="submit" class="btn btn-primary">Decrypt</button>
                                                                            </div>
                                                                        </form>
                                                                        <div id="decryptMessage" class="mt-2"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('documents.download', $file->id) }}">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item delete-btn" href="#" data-url="{{ route('documents.destroy', $file->id) }}"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($folder->children->isEmpty() && $folder->documents->isEmpty())
                                        <div class="list-group-item text-center py-5">
                                            <img src="{{ asset('images/empty-folder.svg') }}" alt="Empty Folder" class="img-fluid mb-3" style="max-width: 100px">
                                            <h5 class="text-muted">This folder is empty</h5>
                                            <p class="text-muted">Upload files or create subfolders to add content</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Edit Folder --}}
                    <div class="modal fade" id="editFolderModal" tabindex="-1" aria-labelledby="editFolderModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" id="edit-folder-form">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Folder</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="name" id="folder-name" class="form-control" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Folders Only Tab -->
                    <div class="tab-pane fade" id="folders-content" role="tabpanel">
                        <!-- List View -->
                        <div class="view-container list-view">
                            <div class="card border-0">
                                <div class="list-group list-group-flush">
                                    @forelse ($folder->children as $sub)
                                        <div class="list-group-item item">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="folder-icon me-3">
                                                            <i class="fas fa-folder fa-lg text-warning"></i>
                                                            @if ($sub->password)
                                                                <span class="position-absolute translate-middle badge badge-lock bg-secondary">
                                                                    <i class="fas fa-lock"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <a href="{{ route('folder.show', $sub->id) }}" class="text-decoration-none text-dark text-truncate">
                                                            {{ $sub->name }}
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-3 d-none d-md-block text-muted small">
                                                    {{ $sub->created_at->format('d M Y H:i') }}
                                                </div>
                                                <div class="col-3 col-md-2 text-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li><a class="dropdown-item" href="#"><i class="fas fa-pen me-2"></i>Edit</a></li>
                                                            <li><a class="dropdown-item" href="#"><i class="fas fa-trash me-2"></i>Delete</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="list-group-item text-center py-5">
                                            <img src="{{ asset('images/empty-folder.svg') }}" alt="Empty Folder" class="img-fluid mb-3" 
                                                 style="max-width: 100px">
                                            <h5 class="text-muted">No subfolders yet</h5>
                                            <p class="text-muted">Click "Create Subfolder" to add a new subfolder</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Files Only Tab -->
                    <div class="tab-pane fade" id="files-content" role="tabpanel">
                        <!-- List View -->
                        <div class="view-container list-view">
                            <div class="card border-0">
                                <div class="list-group list-group-flush">
                                    @foreach ($folder->documents as $file)
                                        <div class="list-group-item item">
                                            <div class="row align-items-center">
                                                <div class="col-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="file-icon me-3">
                                                            @php
                                                                $extension = pathinfo($file->filename, PATHINFO_EXTENSION);
                                                                $iconClass = 'fa-file-alt';
                                                                $iconColor = 'text-info';
                                                                $fileIcons = [
                                                                    ['ext' => ['jpg', 'jpeg', 'png', 'gif', 'svg'], 'icon' => 'fa-file-image', 'color' => 'text-success'],
                                                                    ['ext' => ['doc', 'docx'], 'icon' => 'fa-file-word', 'color' => 'text-primary'],
                                                                    ['ext' => ['xls', 'xlsx'], 'icon' => 'fa-file-excel', 'color' => 'text-success'],
                                                                    ['ext' => ['pdf'], 'icon' => 'fa-file-pdf', 'color' => 'text-danger'],
                                                                    ['ext' => ['zip', 'rar', '7z'], 'icon' => 'fa-file-archive', 'color' => 'text-warning']
                                                                ];
                                                                foreach ($fileIcons as $icon) {
                                                                    if (in_array($extension, $icon['ext'])) {
                                                                        $iconClass = $icon['icon'];
                                                                        $iconColor = $icon['color'];
                                                                        break;
                                                                    }
                                                                }
                                                            @endphp
                                                            <i class="fas {{ $iconClass }} fa-lg {{ $iconColor }}"></i>
                                                            @if ($file->password)
                                                                <span class="position-absolute translate-middle badge badge-lock bg-secondary">
                                                                    <i class="fas fa-lock"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <span class="text-dark text-truncate">{{ $file->original_filename    }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-3 d-none d-md-block text-muted small">
                                                    {{ $file->created_at->format('d M Y H:i') }}
                                                </div>
                                                <div class="col-3 col-md-2 text-end">
                                                    @if ($file->password)
                                                        <button class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="collapse" 
                                                                data-bs-target="#password-list-{{ $file->id }}">
                                                            <i class="fas fa-lock"></i>
                                                        </button>
                                                    @else
                                                        <a class="btn btn-sm btn-outline-primary me-1" href="{{ route('documents.download', $file->id) }}">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    @endif
                                                    <div class="dropdown d-inline-block">
                                                        <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#editModal-{{ $file->id }}">
                                                                    <i class="fas fa-pen me-2"></i>Edit
                                                                </button>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $file->id }}">
                                                                    <i class="fas fa-trash me-2"></i>Delete
                                                                </button>
                                                            </li>

                                                            {{-- edit --}}
                                                            <div class="modal fade" id="editModal-{{ $file->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $file->id }}" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form action="{{ route('documents.update', $file->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="editModalLabel-{{ $file->id }}">Edit Dokumen</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="filename-{{ $file->id }}" class="form-label">Nama Dokumen</label>
                                                                                    <input type="text" class="form-control" id="filename-{{ $file->id }}" name="name" value="{{ $file->name }}" required>
                                                                                </div>
                                                                                <!-- Tambahkan input lainnya jika perlu -->
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>

                                                            {{-- delete --}}
                                                            <div class="modal fade" id="deleteModal-{{ $file->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $file->id }}" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form action="{{ route('documents.destroy', $file->id) }}" method="POST">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="deleteModalLabel-{{ $file->id }}">Konfirmasi Hapus</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                Apakah Anda yakin ingin menghapus dokumen ini?
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($file->password)
                                                <div class="collapse mt-2" id="password-list-{{ $file->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <form action="{{ route('file.access', $file->id) }}" method="POST" class="d-flex gap-2">
                                                                @csrf
                                                                <input type="password" name="password" class="form-control form-control-sm" 
                                                                       placeholder="Password" required>
                                                                <button class="btn btn-sm btn-success">
                                                                    <i class="fas fa-check"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if($folder->documents->isEmpty())
                                        <div class="list-group-item text-center py-5">
                                            <img src="{{ asset('images/empty-folder.svg') }}" alt="Empty Folder" class="img-fluid mb-3" 
                                                 style="max-width: 100px">
                                            <h5 class="text-muted">No files yet</h5>
                                            <p class="text-muted">Click "Upload File" to add a new file</p>
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

<!-- Upload File Modal -->
<div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadFileModalLabel"><i class="fas fa-upload me-2"></i> Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{ Form::open(['url' => route('documents.store'), 'class' => 'form-horizontal', 'files' => true]) }}
            {{Form::hidden ('folder_id', $folder->id)}}
          <div class="card-body">
          <div class="form-group mt-1">
               <label>File Dokumen</label>
               <input type="hidden" name="folder_id" value="{{ $folder->id }}">
               {{ Form::file('pdf', ['class' => 'form-control', 'accept' => 'application/pdf']) }}
               @if ($errors->has('pdf'))
                    <span class="help-block" style="color:red">{{ $errors->first('pdf') }}</span>
               @endif
               @if (!empty($dokumen))
                    <a href="{{ asset('Uploads/' . $dokumen->file_dokumen) }}" download target="_blank"><small class="text-success">Download dokumen <i data-feather="download"></i></small></a>
               @endif
          </div>

          <div class="form-group mt-1">
               <label>Kategori</label>
               {{-- @dump($categories) <!-- Debugging --> --}}
               {{ Form::select('category_id', $categories, null, ['class' => 'form-control', 'placeholder' => 'Select a category']) }}
               @if ($errors->has('category_id'))
                    <span class="help-block" style="color:red">{{ $errors->first('category_id') }}</span>
               @endif
          </div>

          <div class="form-group mt-1">
               <label>Password</label>
               {{ Form::text('secret_key', null, ['class' => 'form-control', 'placeholder' => 'Password (Optional)']) }}
          </div>
          </div>

          <div class="card-footer">
          <div class="form-group">
               <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save Dokumen</button>
               <a href="{{ route('documents.index') }}" class="btn btn-danger"><i class="fas fa-backward"></i> Kembali</a>
          </div>
          </div>

          {!! Form::close() !!}
        </div>
    </div>
</div>

<!-- Create Subfolder Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFolderModalLabel"><i class="fas fa-folder-plus me-1"></i> Create Subfolder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('folder.store') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="parent_id" value="{{ $folder->id }}">
                    <div class="mb-3">
                        <label for="folderName" class="form-label">Subfolder Name</label>
                        <input class="form-control" id="folderName" name="name" placeholder="Enter subfolder name" required>
                    </div>
                    <div class="mb-3">
                        <label for="folderPassword" class="form-label">Password (optional)</label>
                        <input class="form-control" type="password" id="folderPassword" name="password" 
                               placeholder="Leave blank if no password">
                        <div class="form-text">Password-protected folders require a password to access.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Create Subfolder</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Edit modal
    document.querySelectorAll('.edit-folder-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const action = this.dataset.action;

            document.getElementById('folder-name').value = name;
            document.getElementById('edit-folder-form').action = action;

            new bootstrap.Modal(document.getElementById('editFolderModal')).show();
        });
    });

    // SweetAlert delete
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.dataset.url;

            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;

                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Auto-dismiss alerts
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.add('fade');
                alert.addEventListener('transitionend', () => alert.remove());
            });
        }, 5000);

        // View toggle functionality
        const gridViewBtn = document.getElementById('grid-view-btn');
        const listViewBtn = document.getElementById('list-view-btn');
        const viewContainers = document.querySelectorAll('.view-container');

        const toggleView = (view) => {
            gridViewBtn.classList.toggle('active', view === 'grid');
            listViewBtn.classList.toggle('active', view === 'list');
            
            viewContainers.forEach(container => {
                container.classList.toggle('active', 
                    container.classList.contains(`${view}-view`)
                );
            });

            localStorage.setItem('viewMode', view);
        };

        gridViewBtn.addEventListener('click', () => toggleView('grid'));
        listViewBtn.addEventListener('click', () => toggleView('list'));

        // Load saved view mode
        const savedViewMode = localStorage.getItem('viewMode') || 'grid';
        toggleView(savedViewMode);
    });
    $(document).ready(function() {
    $('.decrypt').click(function() {
        var id = $(this).data('id');
        $('#document_id').val(id);
        $('#decryptModal').modal('show');
    });

    $('#decryptForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '{{ route('documents.decrypt') }}',
            method: 'post',
            data: formData,
            success: function(response) {
                // Create a temporary anchor element to trigger the download
                var link = document.createElement('a');
                link.href = response.fileUrl;
                link.download = response.fileUrl.split('/')
            .pop(); // Extract filename from URL
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                $('#decryptMessage').html(
                    '<div class="alert alert-success">Document decrypted and downloaded successfully.</div>'
                    );
                $('#decryptModal').modal('hide');
            },
            error: function(xhr) {
                $('#decryptMessage').html('<div class="alert alert-danger">Error: ' +
                    xhr.responseText + '</div>');
            }
        });
    });
});
</script>
@endpush