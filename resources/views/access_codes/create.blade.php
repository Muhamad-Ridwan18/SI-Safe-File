@extends('layouts.app')
@section('title','Documents')
@section('content')

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
         <div class="content-header row" >
            <div class="content-header-left col-md-9 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Dokumen</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a>
                                </li>
                                <li class="breadcrumb-item active">create
                                </li>
                            </ol>
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
                           <div class="card-body">
                            {{ Form::open(['url'=>route('access_codes.store'),'class'=>'form-horizontal','files'=>true])}}
                              
                                <div class="card-body">
                                   <div class="form-group">
                                        <label for="share" class="control-label">Kode Akses</label>
                                        {{ Form::hidden('document_id', $document->id) }}
                                        {{ Form::text('access_code',null,['class'=>'form-control']) }}
                                   </div>
                                </div>
                                <div class="card-footer">
                                   <div class="form-group">
                                       <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Simpan</button>
                                           
                                       <a href="{{ route('documents.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-backward"></i> Kembali</a>
                                   </div>
                                 </div>
                   
                             {!! Form::close() !!}  
                           </div>
                        </div>
                     </div>
                  </div>
                </section>

            </div>
        </div>
    </div>
@endsection