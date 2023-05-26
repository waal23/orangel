@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/gifts.js') }}"></script>
@endsection
@section('content')



    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Gifts') }}</h4>
               <a class="btn btn-primary addModalBtn ml-auto" data-toggle="modal" data-target="#addcat" href="">{{ __('app.Add_Gift') }}
        </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="table-22">
                    <thead>
                        <tr>
                            <th> {{ __('app.Image') }}</th>
                            <th> {{ __('app.Price') }}</th>
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

                    <h5>{{ __('app.Add_Gift') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" class="add_category" id="addForm"
                        autocomplete="off">
                        @csrf



                        <div class="form-group">
                            <div class="mb-3">
                                <label for="gift_image" class="form-label">{{ __('Image') }}</label>
                                <input id="gift_image" class="form-control" type="file"
                                    accept="image/png, image/gif, image/jpeg" name="image" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Price_coins') }}</label>
                            <input type="number" name="coin_price" class="form-control" required>
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
                    <h5 class="modal-title" id="exampleModalLabel"> {{ __('app.Edit_Gift') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit_cat" autocomplete="off">

                        @csrf
                        <input type="hidden" class="form-control" id="editId" name="id" value="">

                        <img height="150" width="150" class="rounded mb-3" id="gift-img-view" src=""
                            alt="">

                        <div class="form-group">
                            <div class="mb-3">
                                <label for="edit_gift_image" class="form-label">{{ __('Image') }}</label>
                                <input id="edit_gift_image" class="form-control" type="file"
                                    accept="image/png, image/gif, image/jpeg" name="image">
                            </div>
                        </div>

                        <div class="form-group">
                            <label> {{ __('app.Price_coins') }}</label>
                            <input id="edit_coin_price" type="number" name="coin_price" class="form-control" required>
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
