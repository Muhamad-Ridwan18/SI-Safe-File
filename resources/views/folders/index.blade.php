@extends('layouts.app')
@section('title', 'Documents')
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
                            <h2 class="content-header-title float-start mb-0">Data Documents</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('folder.index') }}">Data Documents</a>
                                    </li>
                                    <li class="breadcrumb-item active">Index
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Dashboard Analytics Start -->
                <section id="dashboard-analytics">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card shadow-sm border-0 p-3">
                                    
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-success d-flex align-items-center gap-2 mb-2" data-bs-toggle="modal" data-bs-target="#createFolderModal">
                                            <i class="fas fa-plus-circle fa-lg"></i>
                                            <span>Buat Folder Baru</span>
                                        </button>
                                        <form method="GET" action="{{ route('folder.index') }}"
                                            class="row g-3 align-items-center" id="filterForm">
                                            <div class="col-auto">
                                                <label for="filterDivisi" class="col-form-label fw-semibold">Filter Category:</label>
                                            </div>
                                            <div class="col-auto">
                                                <select id="filterDivisi" name="category_id" class="form-select"
                                                    onchange="document.getElementById('filterForm').submit();">
                                                    <option value="">Semua Kategori</option>
                                                    @foreach ($categories as $divisi)
                                                        <option value="{{ $divisi->id }}"
                                                            {{ request('category_id') == $divisi->id ? 'selected' : '' }}>
                                                            {{ $divisi->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                        
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

                                    <!-- Grid Folder View -->
                                    @if ($folders->count())
                                        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                                            @foreach ($folders as $folder)
                                                <div class="col">
                                                    <div class="card h-100 folder-card border shadow-sm hover-shadow position-relative">
                                                        <!-- Dropdown Menu -->
                                                        @if ($folder->user_id !== '5d759032-37d7-4f6b-b5d2-110b4b521a10')
                                                        <div class="dropdown position-absolute" style="top: 10px; right: 10px; z-index: 20;">
                                                            <button class="btn btn-sm btn-light rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('folder.edit', $folder->id) }}">
                                                                        <i class="fas fa-edit me-2"></i>Edit
                                                                    </a>
                                                                </li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a class="dropdown-item text-danger delete-folder" href="#" 
                                                                        data-id="{{ $folder->id }}" 
                                                                        data-name="{{ $folder->name }}"
                                                                        data-password="{{ $folder->password}}">
                                                                        <i class="fas fa-trash"></i>Hapus
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        @endif

                                                        <a href="{{ route('folder.show', $folder->id) }}" class="text-decoration-none">
                                                            <div class="card-body text-center p-4 position-relative">
                                                                @if($folder->category_id)
                                                                    <span class="badge bg-primary position-absolute" style="top: 10px; left: 10px; z-index: 15;">
                                                                        {{ $folder->category->name }}
                                                                    </span>
                                                                @endif
                                                                <div class="folder-icon mb-3">
                                                                    <i class="fas fa-folder text-warning" style="font-size: 3rem;"></i>
                                                                </div>
                                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                                    <h5 class="card-title mb-0 text-dark fw-medium text-truncate">
                                                                        {{ $folder->name }}
                                                                    </h5>
                                                                    @if ($folder->password)
                                                                        <i class="fas fa-lock text-secondary" data-bs-toggle="tooltip" title="Folder dilindungi password"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light text-center py-5">
                                            <i class="fas fa-folder-open text-muted me-2 fa-2x"></i>
                                            <h5 class="mt-3">
                                                @if(request('category_id'))
                                                    Belum ada folder dalam kategori ini.
                                                @else
                                                    Belum ada folder.
                                                @endif
                                            </h5>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <!--/ List DataTable -->
                </section>
                <!-- Dashboard Analytics end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->

    <!-- BEGIN: Modal -->
    <div class="modal fade" id="decryptModal" tabindex="-1" role="dialog" aria-labelledby="decryptModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="decryptModalLabel">Decrypt Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="decryptForm">
                        @csrf
                        <input type="hidden" name="document_id" id="document_id">
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
    <!-- END: Modal -->

<!-- Modal -->
<div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="createFolderModalLabel">Buat Folder Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('folder.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="folderName" class="form-label fw-semibold">Nama Folder</label>
                        <input id="folderName" class="form-control" name="name" placeholder="Contoh: Dokumen Siswa" required>
                    </div>

                    <div class="mb-3">
                        <label for="divisi" class="form-label fw-semibold">Kategori</label>
                        <select id="divisi" class="form-select" name="category_id">
                            <option value="">-- Pilih Kategori (opsional) --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="folderPassword" class="form-label fw-semibold">Password (opsional)</label>
                        <input id="folderPassword" class="form-control" type="password" name="password" placeholder="Bisa dikosongkan">
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-folder-plus me-1"></i> Buat Folder
                    </button>
                </form>
            </div>
        </div>
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

@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle export form
            $('#toggleExportForm').click(function() {
                $('#exportForm').slideToggle('fast');
            });

            // Handle folder deletion
            $('.delete-folder').click(function(e) {
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
                            swal("Berhasil!", "Subfolder berhasil dihapus!", {
                                icon: "success",
                                buttons: {
                                    confirm: {
                                        className: 'btn btn-success'
                                    }
                                },
                            });
                            location.reload();
                        }
                    });
                }
            });

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
                                $(`[data-id="${id}"]`).closest('.list-group-item').fadeOut(400, function() {
                                    $(this).remove();
                                    location.reload();
                                });
                            });
                        } else {
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

        });
    </script>

    <script>
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

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get all collapse toggles
            const toggles = document.querySelectorAll('[data-bs-toggle="collapse"]');

            toggles.forEach(toggle => {
                // Update icon when collapse state changes
                const targetId = toggle.getAttribute('data-bs-target');
                const target = document.querySelector(targetId);
                const icon = toggle.querySelector('.collapse-icon');

                if (target && icon) {
                    target.addEventListener('show.bs.collapse', function() {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                    });

                    target.addEventListener('hide.bs.collapse', function() {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-right');
                    });
                }
            });
        });
    </script>