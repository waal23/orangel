@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/users.js') }}"></script>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Users') }}</h4>
            <a href="{{ route('addFakeUser') }}" id="add-fake-user" class="ml-auto btn btn-primary">{{ __('app.Add_fake_user') }}</a>
        </div>

        <div class="card-body">

            <div class="tab  " role="tabpanel">
                <ul class="nav nav-pills border-b mb-3  ml-0">

                    <li role="presentation" class="nav-item"><a class="nav-link pointer active" href="#Section1"
                            aria-controls="home" role="tab" data-toggle="tab">{{ __('app.All_Users') }}<span
                                class="badge badge-transparent total_open_complaint"></span></a>
                    </li>

                    <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section2" role="tab"
                            data-toggle="tab">{{ __('app.Streamers') }}
                            <span class="badge badge-transparent total_close_complaint"></span></a>
                    </li>

                    <li role="presentation" class="nav-item"><a class="nav-link pointer" href="#Section3" role="tab"
                            data-toggle="tab">{{ __('app.Fake_Users') }}
                            <span class="badge badge-transparent total_close_complaint"></span></a>
                    </li>

                </ul>

                <div class="tab-content tabs" id="home">
                    {{-- Section 1 --}}
                    <div role="tabpanel" class="tab-pane active" id="Section1">
                        <div class="table-responsive">
                            <table class="table table-striped w-100" id="UsersTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('app.User_Image') }}</th>
                                        <th>{{ __('app.Identity') }}</th>
                                        <th>{{ __('app.Full_Name') }}</th>
                                        <th>{{ __('app.Live_eligible') }}</th>
                                        <th>{{ __('app.Age') }}</th>
                                        <th>{{ __('app.Gender') }}</th>
                                        <th>{{ __('app.BlockUser') }}</th>
                                        <th>{{ __('app.ViewDetails') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Section 2 --}}
                    <div role="tabpanel" class="tab-pane" id="Section2">
                        <div class="table-responsive">
                            <table class="table table-striped w-100" id="StreamersTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('app.User_Image') }}</th>
                                        <th>{{ __('app.Identity') }}</th>
                                        <th>{{ __('app.Full_Name') }}</th>
                                        <th>{{ __('app.Live_eligible') }}</th>
                                        <th>{{ __('app.Age') }}</th>
                                        <th>{{ __('app.Gender') }}</th>
                                        <th>{{ __('app.BlockUser') }}</th>
                                        <th>{{ __('app.ViewDetails') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Section 3 --}}
                    <div role="tabpanel" class="tab-pane" id="Section3">
                        <div class="table-responsive">
                            <table class="table table-striped w-100" id="FakeUsersTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('app.User_Image') }}</th>
                                        <th>{{ __('app.Full_Name') }}</th>
                                        <th>{{ __('app.Identity') }}</th>
                                        <th>{{ __('app.Password') }}</th>
                                        <th>{{ __('app.Age') }}</th>
                                        <th>{{ __('app.Gender') }}</th>
                                        <th>{{ __('app.BlockUser') }}</th>
                                        <th>{{ __('app.ViewDetails') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
@endsection
