@extends('layouts.app')
@section('title','My Access Requests')
@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-1">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">My Access Requests</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                                <li class="breadcrumb-item active">Access Requests</li>
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
                            <div class="card-header">
                                <h4 class="card-title">My Access Requests</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Document</th>
                                                <th>Requester</th>
                                                <th>Approver</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($accessRequests as $request)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $request->document->title }}</td>
                                                    <td>{{ $request->requester->name }}</td>
                                                    <td>{{ $request->approver->name }}</td>
                                                    <td>{{ ucfirst($request->status) }}</td>
                                                    <td>
                                                        
                                                        <form action="{{ route('access-requests.approve', $request->id) }}" method="post" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                                        </form>
                                                        <form action="{{ route('access-requests.deny', $request->id) }}" method="post" style="display:inline;">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm">Deny</button>
                                                        </form>
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
            </section>
        </div>
    </div>
</div>

@endsection
