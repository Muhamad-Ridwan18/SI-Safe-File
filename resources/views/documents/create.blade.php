@extends('layouts.app')
@section('title', 'Documents')
@section('content')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-1">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Dokumen</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Dokumen</a></li>
                                    <li class="breadcrumb-item active">Create</li>
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
                                    {{ Form::open(['url' => route('documents.store'), 'class' => 'form-horizontal', 'files' => true]) }}

                                    <div class="card-body">
                                        <div class="form-group mt-1">
                                            <label>File Dokumen</label>
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
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection