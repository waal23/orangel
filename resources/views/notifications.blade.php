@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/notification.js') }}"></script>
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Notifications') }}</h4>
            <a class="btn btn-primary addModalBtn ml-auto" data-toggle="modal" data-target="#addcat"
                href="">{{ __('app.Send_notification') }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-22">
                    <thead>
                        <tr>
                            <th> {{ __('app.Title') }}</th>
                            <th> {{ __('app.Message') }}</th>
                            <th> {{ __('app.Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addcat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5>{{ __('app.Send_notification') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" class="add_category" id="addForm"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label> {{ __('app.Title') }}</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>


                        <div class="form-group">
                            <label> {{ __('app.Message') }}</label>
                            <textarea type="text" name="message" class="form-control" required></textarea>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('app.Send_notification') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="edit_cat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('app.EditPackage') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit_cat" autocomplete="off">

                        @csrf
                        <input type="hidden" class="form-control" id="editId" name="id" value="">

                        <div class="form-group">
                            <label>{{ __('app.Title') }}</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Message') }}</label>
                            <textarea type="text" id="edit_message" name="message" class="form-control" required></textarea>
                        </div>


                        <div class="form-group">
                            <input type="submit" class=" btn btn-primary">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
