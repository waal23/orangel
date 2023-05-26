@extends('include.app')
@section('header')
    <script src="{{ asset('asset/script/setting.js') }}"></script>
@endsection

@section('content')
    <?php
    
    $admob = json_decode($admob, true);
    ?>

    <div class="card  ">
        <div class="card-header">
            <h4>{{ __('app.Appdata') }}</h4>
            <div class="border-bottom-0 border-dark border"></div>
        </div>
        <div class="card-body">

            <form Autocomplete="off" class="form-group form-border appdataForm" action="" method="post">

                @csrf

                <input type="hidden" name="admob_id" value="1">


                @csrf
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">{{ __('app.Currency') }}</label>
                        <input type="text" class="form-control" name="currency" value="{{ $appdata->currency }}"
                            required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">{{ __('app.Minimum_Threshold') }}</label>
                        <input type="text" class="form-control" name="min_threshold"
                            value="{{ $appdata->min_threshold }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">{{ __('app.Coin_Rate') }}</label>
                        <input type="number" step="0.000001" class="form-control" name="coin_rate"
                            value="{{ $appdata->coin_rate }}" required>
                    </div>


                </div>

                <div class="form-row">

                    <div class="form-group col-md-6">
                        <label for="">{{ __('app.Minimum_users_needed') }}</label>
                        <input type="text" class="form-control" name="min_user_live"
                            value="{{ $appdata->min_user_live }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="">{{ __('app.Maximum_minutes_for_live') }}</label>
                        <input type="text" class="form-control" name="max_minute_live"
                            value="{{ $appdata->max_minute_live }}" required>
                    </div>

                </div>


                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="">{{ __('app.Message_price') }}</label>
                        <input type="number" class="form-control" name="message_price"
                            value="{{ $appdata->message_price }}" pattern="[0-9]" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('app.Reverse_swipe_price') }}</label>
                        <input type="number" class="form-control" name="reverse_swipe_price"
                            value="{{ $appdata->reverse_swipe_price }}" pattern="[0-9]" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">{{ __('app.Live_watching_price') }}</label>
                        <input type="number" class="form-control" name="live_watching_price"
                            value="{{ $appdata->live_watching_price }}" pattern="[0-9]" required>
                    </div>

                </div>

                <div class="my-4">
                    <h5 class="text-dark">{{ __('Admob Ad Units') }}</h5>
                </div>
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">Admob Banner Ad Unit : Android</label>
                        <input type="text" class="form-control" name="admob_banner"
                            value="{{ $appdata->admob_banner }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Admob Interstitial Ad Unit : Android</label>
                        <input type="text" class="form-control" name="admob_int" value="{{ $appdata->admob_int }}">
                    </div>
                </div>
                <div class="form-row ">
                    <div class="form-group col-md-4">
                        <label for="">Admob Banner Ad Unit : iOS</label>
                        <input type="text" class="form-control" name="admob_banner_ios"
                            value="{{ $appdata->admob_banner_ios }}">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="">Admob Interstitial Ad Unit : iOS</label>
                        <input type="text" class="form-control" name="admob_int_ios"
                            value="{{ $appdata->admob_int_ios }}">
                    </div>
                </div>



                <div class="form-group-submit">
                    <button class="btn btn-primary " type="submit">{{ __('app.Save') }}</button>
                </div>

            </form>

        </div>
    </div>
@endsection
