@extends('layouts.app')

@section('content')
<div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Selamat datang, <b>{{ Auth::user()->name }}</h4>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>
</div>


@endsection
