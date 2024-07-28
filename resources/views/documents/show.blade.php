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
                            <h2 class="content-header-title float-start mb-0">Documents</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a>
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
                                <div class="card-body">
                                     <p><strong>Original Filename:</strong> {{ $document->original_filename }}</p>
                                     <p><strong>Encrypted Filename:</strong> {{ $document->encrypted_filename }}</p>
                                     <a href="{{ route('documents.download', $document->id) }}" class="btn btn-primary">Download Encrypted File</a>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">     
                                   <h2>Test Avalanche Effect</h2>
                                   <form action="{{ route('documents.test_avalanche', $document->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">Test Avalanche Effect</button>
                                   </form>

                                   @if (isset($avalancheResults))
                                        <h2 class="mt-2">Avalanche Effect Test Results</h2>
                                        <table class="table">
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
                                             <tbody>
                                                  <tr>
                                                       <td>{{ $avalancheResults['originalFileName'] }}</td>
                                                       <td>{{ $avalancheResults['encryptedFileName'] }}</td>
                                                       <td>{{ $avalancheResults['originalFileSize'] }}</td>
                                                       <td>{{ $avalancheResults['modifiedFileSize'] }}</td>
                                                       <td>{{ $avalancheResults['bitDifference'] }}</td>
                                                       <td>{{ $avalancheResults['percentageDifference'] }}%</td>
                                                  </tr>
                                             </tbody>
                                        </table>
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
@endpush
