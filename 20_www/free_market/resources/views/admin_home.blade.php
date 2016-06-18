@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in Admin!
                    <br>
                    <a href="/api/users" />http://192.168.33.10/api/users</a><br>
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
