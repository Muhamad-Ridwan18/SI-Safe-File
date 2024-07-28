@extends('layouts.app')
@section('title','Documents')
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
                            <h2 class="content-header-title float-start mb-0">Dekripsi Dokument</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dekripsi Dokument</a>
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
                                    <h4 class="card-title">Data Terenkripsi</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="decrypt-table" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%">No</th>
                                                    <th>Document Name</th>
                                                    <th>Document Encrypt</th>
                                                    <th>Secret Key</th>
                                                    <th style="width: 20%" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($documents as $document)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $document->original_filename }}</td>
                                                    <td>
                                                        <a href="{{ route('documents.download', $document->id) }}">{{ $document->encrypted_filename }}</a>
                                                    </td>
                                                    <td>{{ $document->secret_key }}</td>
                                                    <td class="text-center">
                                                        <div class="form-button-action">
                                                            <button type="button" class="btn btn-link btn-warning btn-sm decrypt" data-id="{{ $document->id }}">
                                                                <i data-feather='unlock'></i>
                                                            </button>
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

    <div class="modal fade" id="decryptModal" tabindex="-1" role="dialog" aria-labelledby="decryptModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="decryptModalLabel">Decrypt Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="decryptMessage"></div>
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
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>

$(document).ready(function() {

    $('.delete').click(function(e) {
        var id = $(this).data('id');
        swal({
            title: 'Are you sure?',
            text: "Data will be permanently deleted!",
            type: 'warning',
            buttons:{
                confirm: {
                    text : 'Yes, I am sure!',
                    className : 'btn btn-success'
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
                        "id" : id
                    },
                    success: function(data){
                        swal("Good job!", "You clicked the button!", {
                            icon : "success",
                            buttons: {
                                confirm: {
                                    className : 'btn btn-success'
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
                    link.download = response.fileUrl.split('/').pop(); // Extract filename from URL
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    $('#decryptMessage').html('<div class="alert alert-success">Document decrypted and downloaded successfully.</div>');
                    $('#decryptForm')[0].reset();
                    $('#decryptModal').modal('hide');

                    setTimeout(function() {
                        $('#decryptMessage').html('');
                    }, 3000);
                },
                error: function(xhr) {
                    $('#decryptMessage').html('<div class="alert alert-danger">Error: ' + xhr.responseText + '</div>');

                    setTimeout(function() {
                        $('#decryptMessage').html('');
                    }, 3000);
                }
            });
        });
    });
</script>

    
@endpush
