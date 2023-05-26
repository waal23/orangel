@extends('include.app')

@section('header')
    <script src="{{ asset('asset/script/viewuser.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('asset/style/addFakeUser.css') }}">
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>
                <?php
                echo $data['fullname'];
                
                if ($data['can_go_live'] == 2) {
                    echo '<span class="ml-2 badge bg-success text-white  ">Can Go Live</span>';
                } else {
                    // echo '<span class="ml-2 badge bg-danger text-white  ">Not Eligible To Go Live</span>';
                }
                if ($data['is_fake'] == 1) {
                    echo '<span class="ml-2 badge bg-black text-white  ">Fake User</span>';
                } else {
                    echo '<span class="ml-2 badge bg-success text-white  ">Real User</span>';
                }
                ?>

            </h4>

            <div class="d-flex ml-auto">
                <?php if ($data['gender'] == 1) {
                    echo "<h2 class='btn btn-primary'>" . __('app.Male') . '</h2>';
                } else {
                    echo "<h2 class='btn btn-primary'>" . __('app.Female') . '</h2>';
                }
                ?>
                <?php if ($data['is_block'] == 1) {
                    echo "<h2 class='btn btn-success ml-2 unblock' rel='" . $data['id'] . "'>" . __('app.Unblock') . '</h2>';
                } else {
                    echo "<h2 class='btn btn-danger ml-2  block' rel='" . $data['id'] . "'>" . __('app.Block') . '</h2>';
                }
                ?>
                <?php if ($data['can_go_live'] == 2) {
                    echo "<h2 class='btn btn-danger ml-2 restrict-live' rel='" . $data['id'] . "'>" . __('app.Restrict_live') . '</h2>';
                } else {
                    echo "<h2 class='btn btn-success ml-2 allow-live' rel='" . $data['id'] . "'>" . __('app.Allow_live') . '</h2>';
                }
                ?>


            </div>
        </div>

        <div class="card-body">

            <div class="form-row mb-3 ">
                @foreach ($data['images'] as $image)
                    {{-- <img class="rounded m-1 " src="{{ env('image') }}{{ $image->image }}" width="130" height="130"> --}}
                    <div class="borderwrap2 " data-href="">
                        <div class="filenameupload2">
                            <img class="rounded " src="{{ env('image') }}{{ $image->image }}" width="130"
                                height="130">
                            <div data-imgid="{{ $image->id }}" class="middle btnRemove"><i
                                    class="material-icons remove_img2">cancel</i>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mb-4">
                <button id="btnAddImage" class="btn btn-primary">Add Image</button>
            </div>

            <form method="post" class="" action="" id="userUpdate" enctype="multipart/form-data">
                @csrf

                <input class=" form-control" readonly name="id" value="{{ $data['id'] }}" type="text"
                    id="userId" hidden>

                <div class="form-row">
                    @if ($data['is_fake'] == 0)
                        <div class="form-group col-md-6">
                            <label>{{ __('app.Identity') }}</label>
                            <input name="identity" class="form-control" value="{{ $data['identity'] }}" readonly>
                        </div>
                    @else
                        <div class="form-group col-md-3">
                            <label>{{ __('app.Identity') }}</label>
                            <input name="identity" class="form-control" value="{{ $data['identity'] }}" readonly>
                        </div>
                        <div class="form-group col-md-3">
                            <label>{{ __('app.Password') }}</label>
                            <input name="password" class="form-control" value="{{ $data['password'] }}">
                        </div>
                    @endif

                    <div class="form-group col-md-3">
                        <label>{{ __('app.Full_Name') }}</label>
                        <input name="fullname" class="form-control" value="{{ $data['fullname'] }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label>{{ __('app.Age') }}</label>
                        <input name="age" class="form-control" value="{{ $data['age'] }}">
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ __('app.Youtube') }}</label>
                        <input name="youtube" class="form-control" value="{{ $data['youtube'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ __('app.Instagram') }}</label>
                        <input name="instagram" class="form-control" value="{{ $data['instagram'] }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ __('app.Facebook') }}</label>
                        <input name="facebook" class="form-control" value="{{ $data['facebook'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>{{ __('app.Live') }}</label>
                        <input name="live" class="form-control" value="{{ $data['live'] }}">
                    </div>
                </div>


                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>{{ __('app.Bio') }} </label>
                        <textarea name="bio" class="form-control">{{ $data['bio'] }}</textarea>

                    </div>
                    <div class="form-group col-md-6">
                        <label for="product_description">{{ __('app.About') }} </label>
                        <textarea name="about" class="form-control">{{ $data['about'] }}</textarea>

                    </div>
                </div>
                <div class="form-row">
                    <button type="submit" class="btn btn-primary">{{ __('app.Submit') }}</button>
                </div>

            </form>

        </div>


    </div>

    <div class="modal fade" id="addImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5>{{ __('app.Add_Image') }}</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="" method="post" enctype="multipart/form-data" class="add_category" id="addForm"
                        autocomplete="off">
                        @csrf
                        <input class=" form-control" readonly name="id" value="{{ $data['id'] }}" type="text"
                            id="userId" hidden>

                        <div class="form-group">
                            <div class="mb-3">
                                <label for="gift_image" class="form-label">{{ __('Image') }}</label>
                                <input id="gift_image" class="form-control" type="file"
                                    accept="image/png, image/gif, image/jpeg" name="image" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <input class="btn btn-primary mr-1" type="submit" value=" {{ __('app.Submit') }}">
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
