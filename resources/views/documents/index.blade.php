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
                                <div class="card-header">
                                    <h4 class="card-title">Data Dokumen</h4>
                                    <div class="d-flex gap-1">
                                        <button id="toggleExportForm" class="btn btn-info">
                                            <i class="fa fa-file-export"></i> Export 
                                        </button>
                                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                                            <i class="fa fa-plus"></i> Add
                                        </a>
                                        {{-- <a href="{{ route('documents.export') }}" class="btn btn-success">Export to Excel</a> --}}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('documents.do-export') }}" id="exportForm" style="display: none;">
                                        @csrf

                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Filter by Category
                                                (Optional)</label>
                                            <select class="form-select" id="category_id" name="category_id">
                                                <option value="">All Categories</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="user_id" class="form-label">Filter by User (Optional, Admin
                                                only)</label>
                                            <select class="form-select" id="user_id" name="user_id">
                                                <option value="">All Users</option>
                                                @foreach (\App\Models\User::all() as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        {{-- @if (auth()->user()->isAdmin())
                                        @endif --}}

                                        <div class="mb-3">
                                            <label for="export_filename" class="form-label">Export Filename
                                                (Optional)</label>
                                            <input type="text" class="form-control" id="export_filename"
                                                name="export_filename" placeholder="documents-export.xlsx">
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
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%">No</th>
                                                    <th>Document Name</th>
                                                    <th>Category</th>
                                                    <th style="width: 20%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($documents as $document)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><a
                                                                href="{{ route('documents.download', $document->id) }}">{{ $document->original_filename }}</a>
                                                        </td>
                                                        <td>{{ $document->category->name ?? '-' }}</td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center gap-1">
                                                                @if ($document->secret_key)
                                                                    <div class="form-button-action">
                                                                        <button type="button"
                                                                            class="btn btn-link btn-warning decrypt"
                                                                            data-id="{{ $document->id }}">
                                                                            <i data-feather='unlock'></i>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                                <div class="form-button-action">
                                                                    <button type="button"
                                                                        class="btn btn-link btn-danger delete"
                                                                        data-id="{{ $document->id }}">
                                                                        <i data-feather='trash-2'></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
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
                            url: '{{ route('documents.delete') }}',
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
@endpush