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
                                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Data Documents</a>
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
                                                    <option value="">Semua Divisi</option>
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


                                    <!-- Grid Folder View -->
                                    @if ($folders->count())
                                        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                                            @foreach ($folders as $folder)
                                                <div class="col">
                                                    <a href="{{ route('folder.show', $folder->id) }}"
                                                        class="text-decoration-none">
                                                        <div class="card h-100 folder-card border shadow-sm hover-shadow">
                                                            <div class="card-body text-center p-4">
                                                                <div class="folder-icon mb-3">
                                                                    <i class="fas fa-folder text-warning"
                                                                        style="font-size: 3rem;"></i>
                                                                </div>
                                                                <div
                                                                    class="d-flex justify-content-center align-items-center gap-2">
                                                                    <h5
                                                                        class="card-title mb-0 text-dark fw-medium text-truncate">
                                                                        {{ $folder->name }}</h5>
                                                                    @if ($folder->password)
                                                                        <i class="fas fa-lock text-secondary"
                                                                            data-bs-toggle="tooltip"
                                                                            title="Folder dilindungi password"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="alert alert-light text-center py-5">
                                            <i class="fas fa-folder-open text-muted me-2 fa-2x"></i>
                                            <h5 class="mt-3">Belum ada folder.</h5>
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
                        <label for="divisi" class="form-label fw-semibold">Divisi</label>
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Toggle export form
            $('#toggleExportForm').click(function() {
                $('#exportForm').slideToggle('fast');
            });

            $('.delete').click(function(e) {
                var id = $(this).data('id');
                swal({
                    title: 'Are you sure?',
                    text: "Data will be permanently deleted!",
                    type: 'warning',
                    buttons: {
                        confirm: {
                            text: 'Yes, I am sure!',
                            className: 'btn btn-success'
                        },
                        cancel: {
                            visible: true,
                            className: 'btn btn-danger'
                        }
                    }
                }).then((Delete) => {
                    if (Delete) {
                        $.ajax({
                            url: `{{ url('documents') }}/` + id,
                            method: 'post',
                            cache: false,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "id": id
                            },
                            success: function(data) {
                                swal("Good job!", "You clicked the button!", {
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
                    } else {
                        swal.close();
                    }
                });
            });

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
@endpush
