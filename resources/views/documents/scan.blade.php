@extends('layouts.app')
@section('title','Documents')
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
                                <li class="breadcrumb-item active">Scan</li>
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
                            @if ($errors->any())
                            <div class="card-body">
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                                
                                {{ Form::open(['url' => route('documents.access', $document->id), 'class' => 'form-horizontal', 'id' => 'documentForm']) }}

                                <div class="card-body">
                                    <div class="form-group">
                                        <div id="qr-reader" style="width:500px"></div>
                                    </div>
                                    <div style="width: 500px" id="reader"></div>
                                    <div class="form-group mt-2">
                                        <label for="access_code" class="control-label">Data Enkripsi</label>
                                        {{ Form::text('access_code', null, ['class'=>'form-control', 'id' => 'access_code', 'readonly']) }}
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-save"></i> Submit</button>
                                        <a href="{{ route('documents.index') }}" class="btn btn-danger btn-sm"><i class="fas fa-backward"></i> Kembali</a>
                                    </div>
                                </div>

                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
    function docReady(fn) {
        // see if DOM is already available
        if (document.readyState === "complete"
            || document.readyState === "interactive") {
            // call on next available tick
            setTimeout(fn, 1);
        } else {
            document.addEventListener("DOMContentLoaded", fn);
        }
    }

    docReady(function () {
        var accessCodeInput = document.getElementById('access_code');
        var lastResult, countResults = 0;

        function onScanSuccess(decodedText, decodedResult) {
            if (decodedText !== lastResult) {
                ++countResults;
                lastResult = decodedText;
                // Set the scanned QR code text to the access_code input
                accessCodeInput.value = decodedText;
                console.log(`Scan result ${decodedText}`, decodedResult);
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    });
</script>

@endpush
