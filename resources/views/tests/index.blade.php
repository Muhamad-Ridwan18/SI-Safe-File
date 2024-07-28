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
                            <h2 class="content-header-title float-start mb-0">Test avalanche</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('test.index') }}">Test avalanche</a>
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
                                        <table id="basic-datatables-2" class="display table table-striped table-hover">
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
                                                @foreach($document as $document)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $document->original_filename }}</td>
                                                    <td>
                                                        {{ $document->encrypted_filename }}
                                                    </td>
                                                    <td>{{ $document->secret_key }}</td>
                                                    <td class="text-center">
                                                        <div class="form-button-action">
                                                            
                                                             <form action="{{ route('documents.test_avalanche', $document->id) }}" method="POST">
                                                                 @csrf
                                                                 <button type="submit" class="btn btn-primary">Test Avalanche Effect</button>
                                                            </form>
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
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Hasil Test</h4>
                                    
                                </div>
                                <div class="card-body">
                                    <!-- Add a div for displaying the results -->
                                        <div class="table-responsive">
                                             <table id="basic-datatables" class="display table table-striped table-hover">
                                             <thead>
                                                  <tr>
                                                       <th>Original File</th>
                                                       <th>Modified File</th>
                                                       <th>Original File Size (bytes)</th>
                                                       <th>Modified File Size (bytes)</th>
                                                       <th>Bit Difference</th>
                                                       <th>Percentage Difference (%)</th>
                                                  </tr>
                                             </thead>
                                             <tbody id="avalancheResults">
                                                  @if (isset($avalancheResults))
                                                       <tr>
                                                            <td>{{ $avalancheResults['originalFileName'] }}</td>
                                                            <td>{{ $avalancheResults['encryptedFileName'] }}</td>
                                                            <td>{{ $avalancheResults['originalFileSize'] }}</td>
                                                            <td>{{ $avalancheResults['modifiedFileSize'] }}</td>
                                                            <td>{{ $avalancheResults['bitDifference'] }}</td>
                                                            <td>{{ $avalancheResults['percentageDifference'] }}%</td>
                                                       </tr>
                                                  @else
                                                       <tr>
                                                            <td colspan="6">No avalanche results found.</td>
                                                       </tr>
                                                  @endif
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
<div class="modal fade" id="decryptModal" tabindex="-1" role="dialog" aria-labelledby="decryptModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="decryptModalLabel">Decrypt Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
    $('form[action*="test_avalanche"]').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function(response) {
                var results = response.avalancheResults;
                var resultsRow = '<tr>'
                    + '<td>' + results.originalFileName + '</td>'
                    + '<td>' + results.encryptedFileName + '</td>'
                    + '<td>' + results.originalFileSize + '</td>'
                    + '<td>' + results.modifiedFileSize + '</td>'
                    + '<td>' + results.bitDifference + '</td>'
                    + '<td>' + results.percentageDifference + '%</td>'
                    + '</tr>';

                $('#avalancheResults').html(resultsRow);
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseText);
            }
        });
    });
});

    </script>
    
@endpush
