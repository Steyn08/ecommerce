@extends('layouts.app')

@section('content')
<div class="container">
    <main class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Products</h5>
                        <p class="card-text">Manage your products and categories.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Users</h5>
                        <p class="card-text">Manage registered users.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Orders</h5>
                        <p class="card-text">View and manage orders.</p>
                    </div>
                </div>
            </div>
        </div>

        <h2>New Customers</h2>
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>name</th>
                        <th>email</th>
                        <th>Activity</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($users) && count($users) > 0)
                    @foreach ($users as $user)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$user->name ?? '-'}}</td>
                        <td>{{$user->email?? '-'}}</td>
                        <td>Registered</td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center">No data</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>
</div>
@endsection