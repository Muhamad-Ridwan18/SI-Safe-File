@extends('layouts.app')
@section('title','Documents')
@section('content')

    <!-- BEGIN: Content-->
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
         <div class="content-header row" >
            <div class="content-header-left col-md-9 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Dokumen di terima</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen diterima</a>
                                </li>
                                <li class="breadcrumb-item active">index
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
                              <h4 class="card-title">Data Dokumen di terima</h4>
                          </div>
                           <div class="card-body">
                              <div class="table-responsive">
                                 <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                       <tr>
                                          <th style="width: 5%">No</th>
                                          <th>Nama Dokumen</th>
                                          <th>File Dokumen</th>
                                          <th>Upload By</th>
                                          <th>Access Code</th>
                                          <th style="width: 20%" class="text-center">Action</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sharedDocuments as $row)
                                       <tr>
                                          <td>{{ $loop->iteration }}</td>
                                          <td>{{ $row->document->title }}</td>
                                          <td>
                                             @if ($row->document->user_id == Auth::user()->id)
                                                @if ($row->document->accessCodes->count() > 0)
                                                   @foreach ($row->document->accessCodes as $accessCode)
                                                      @if ($accessCode->qr_code_path)
                                                         <form action="{{ route('documents.access', $row->document->id)}}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="access_code" value="{{ $accessCode->access_code }}">
                                                            <button type="submit" class="btn btn-success btn-sm mt-1"><i class="fas fa-download"></i>{{ $row->document->file_path }}</button>
                                                         </form>
                                                      @else
                                                         {{ '-' }}
                                                      @endif
                                                   @endforeach
                                                @endif
                                             @else
                                                <a href="{{ route('documents.scan', $row->document->id) }}">{{ $row->document->file_path }}</a>
                                             @endif
                                          </td>

                                          <td>{{ $row->document->user->name ?? '-' }}</td>
                                          <td>
                                             @if ($row->document->accessCodes->count() > 0)
                                                @foreach ($row->document->accessCodes as $accessCode)
                                                   @if ($accessCode->qr_code_path)
                                                      @if ($row->document->user_id == auth()->id())
                                                            <form action="{{ route('documents.download-qrcode', $row->document) }}" method="get">
                                                               @csrf
                                                               <button class="btn btn-primary btn-sm" type="submit">Download</button>
                                                            </form>
                                                      @else
                                                            @php
                                                               $accessRequest = \App\Models\AccessRequest::where('document_id', $row->document_id)
                                                                                 ->where('requester_id', auth()->id())
                                                                                 ->first();
                                                            @endphp
                                                            @if ($accessRequest && $accessRequest->status == 'approved')
                                                               <form action="{{ route('documents.download-qrcode', $row->document) }}" method="post">
                                                                  @csrf
                                                                  <button class="btn btn-primary btn-sm" type="submit">Download</button>
                                                               </form>
                                                            @elseif ($accessRequest && $accessRequest->status == 'denied')
                                                               <button class="btn btn-warning btn-sm" disabled>Request Denied</button>
                                                            @else
                                                               <form action="{{ route('documents.request-qrcode', $row->document) }}" method="post">
                                                                  @csrf
                                                                  <button class="btn btn-secondary btn-sm" type="submit">Request</button>
                                                               </form>
                                                            @endif
                                                      @endif
                                                   @else
                                                      {{ '-' }}
                                                   @endif
                                                @endforeach
                                             @endif
                                          </td>
                                         <td class="text-center">
                                             <div class="form-button-action">
                                                <button type="button" class="btn btn-link btn-danger btn-sm delete" data-id="{{ $row->id }}">
                                                  <i data-feather='trash-2'></i>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {

   $('.delete').click(function(e) {
      var id = $(this).data('id'); 
      swal({
         title: 'Apakah kamu yakin ?',
         text: "Data akan terhapus secara permanen !",
         type: 'warning',
         buttons:{
            confirm: {
               text : 'Ya, saya yakin!',
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
                  "id" :id
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