@extends('include.app')
@section('header')

    <script src="{{ asset('asset/script/liveApplication.js') }}"></script>
@endsection
@section('content')


    {{-- <div class="text-right mb-3">
        <a class="btn btn-primary addModalBtn" data-toggle="modal" data-target="#addcat"
            href="">{{ __('app.Live_applications') }}
        </a>
    </div> --}}


    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Live_applications') }}</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-22">
                    <thead>
                        <tr>
                            <th> {{ __('app.User_Image') }}</th>
                            <th> {{ __('app.User') }}</th>
                            <th> {{ __('app.Languages') }}</th>
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

                    <h3>{{ __('app.Add_Pack') }}</h3>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" class="add_category" id="addForm"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label> {{ __('app.Diamond_Amount') }}</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>


                        <div class="form-group">
                            <label> {{ __('app.Price') }}</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Playstoreid') }}</label>
                            <input type="text" name="android_product_id" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Appstoreid') }}</label>
                            <input type="text" name="ios_product_id" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('app.Add_Pack') }}">
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
                            <label> {{ __('app.Diamond_Amount') }}</label>
                            <input type="number" id="edit_amount" name="amount" class="form-control" required>
                        </div>


                        <div class="form-group">
                            <label> {{ __('app.Price') }}</label>
                            <input type="number" id="edit_price" name="price" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Playstoreid') }}</label>
                            <input type="text" id="edit_playstore" name="android_product_id" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Appstoreid') }}</label>
                            <input type="text" id="edit_appstore" name="ios_product_id" class="form-control" required>
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
