@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
    <div class="row gy-4">
        <!-- Gamification Card -->
        <div class="col-md-12 col-lg-8">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-md-6 order-2 order-md-1">
                        <div class="card-body">
                            <h4 class="card-title pb-xl-2">Congratulations <strong> {{ Auth::user()->name }}</strong>🎉</h4>
                            <p class="mb-0">You have done <span class="fw-semibold">68%</span>😎 more sales today.</p>
                            <p>Check your new badge in your profile.</p>
                            <a href="javascript:;" class="btn btn-primary">View Profile</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                        <div class="card-body pb-0 px-0 px-md-4 ps-0">
                            <img src="../../assets/img/illustrations/illustration-john-light.png" height="180"
                                alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png"
                                data-app-dark-img="illustrations/illustration-john-dark.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Gamification Card -->     

        <!-- Online Users Card -->
        <div class="col-md-12 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Online Users</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach ($onlineUsers as $user)
                            <li class="list-group-item">
                                <span style="display: inline-block; width: 10px; height: 10px; background-color: #28a745; border-radius: 50%; margin-right: 8px;"></span>
                                {{ $user->name }} ({{ $user->roles->first()->name }})
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <!--/ Online Users Card -->
    </div>
@endsection