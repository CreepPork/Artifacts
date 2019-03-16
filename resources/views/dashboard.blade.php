@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @include('includes.messages')

                        You are logged in!

                    </div>
                </div>

                <passport-clients class="mb-3"></passport-clients>
                <passport-authorized-clients class="mb-3"></passport-authorized-clients>
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </div>
        </div>
    </div>
@endsection
