@extends('include.app')

@section('header')
    <script src="{{ asset('asset/script/interest.js') }}"></script>
@endsection

@section('content')
    <div class="modal fade" id="addcat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class=" ">
                        <h5>{{ __('app.AddInterest') }} </h5>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">



                    <div class="">
                        <form action="" method="post" enctype="multipart/form-data" class="add_category"
                            id="addForm" autocomplete="off">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('app.Title') }} </label>
                                <input type="text" id="cat_title" name="title" class="form-control" required>
                            </div>



                            <div class="form-group text-right">
                                <input class="btn btn-success mr-1" type="submit" id="addcat2"
                                    value="{{ __('app.Save') }}">
                                <a href="#" class="btn btn-light text-dark" data-dismiss="modal"
                                    aria-label="Close">{{ __('app.Close') }} </a>
                            </div>

                        </form>


                    </div>



                </div>

            </div>
        </div>
    </div>




    <div class="card">
        <div class="card-header">
            <h4>{{ __('app.Interests') }}</h4>
            <a class="btn btn-primary InterestsaddModalbtn ml-auto" href="" data-toggle="modal"
                data-target="#addcat">{{ __('app.AddInterest') }}
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped w-100" id="interestTable">
                    <thead>
                        <tr>
                            <th>{{ __('app.Title') }}</th>
                            <th>{{ __('app.Action') }}</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade" id="edit_cat_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ __('app.EditInterest') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data" id="edit_cat" autocomplete="off">

                        @csrf
                        <input type="hidden" class="form-control" id="editcatid" name="id" value="">

                        <div class="form-group">
                            <label for="">{{ __('app.Title') }}</label>
                            <input type="text" class="form-control" id="editcat_title" name="title" required>
                        </div>


                        <div class="form-group text-right">
                            <input type="submit" value="{{ __('app.Save') }}" class=" btn btn-success" id="editcat2">
                            <a href="#" class="btn btn-light text-dark" data-dismiss="modal"
                                aria-label="Close">{{ __('app.Close') }}</a>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
