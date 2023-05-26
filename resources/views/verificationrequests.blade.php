@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/verificationrequests.js') }}"></script>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Verification_requests') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-22">
                    <thead>
                        <tr>
                            <th>{{ __('User Image') }}</th>
                            <th>{{ __('Selfie') }}</th>
                            <th>{{ __('Document') }}</th>
                            <th>{{ __('Document Type') }}</th>
                            <th>{{ __('Full Name') }}</th>
                            <th>{{ __('Identity') }}</th>
                            <th> {{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Preview Modal --}}
    <div class="modal fade" id="previewItem" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h3>{{ __('Image Preview') }}</h3>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="img-preview" width="100%" src="" alt="">
                </div>

            </div>
        </div>
    </div>
@endsection
