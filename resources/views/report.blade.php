@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/report.js') }}"></script>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Reports') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" style="width: 100% !important;" id="UsersTable">
                    <thead>
                        <tr>
                            <th>{{ __('app.Image') }} </th>
                            <th>{{ __('app.Identity') }} </th>
                            <th>{{ __('app.Full_Name') }} </th>
                            <th>{{ __('app.Reason') }}</th>
                        <th>{{ __('app.Description') }}</th>
                            <th>{{ __('app.BlockUser') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
