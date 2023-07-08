@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
@endpush


@push('js')
    <script data-turbolinks-eval="false" data-turbo-eval="false" src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script data-turbolinks-eval="false" data-turbo-eval="false" src="{{ asset('assets/js/init-select2.js') }}"></script>
@endpush

@section('content')
    <div class="ui-container">
        <div class="row">
            <div class="col-lg-7 col-lg-offset-3">
                <section class="panel">
                    <header class="panel-heading">
                        {{ $title }}
                    </header>
                    <div class="panel-body">
                        @if(session('success'))
                            {!! alert_success(session('success')) !!}
                        @elseif(session('error'))
                            {!! alert_error(session('error')) !!}
                        @endif

                            <form id="validate" method="post" action="{{ route('price_settings.update') }}">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}

                                <div class="form-group">
                                    <label>Available Price</label>
                                    <select class="form-control price-settings" name="price_settings[]" multiple>
                                        <option {{ $price_settings->selling_price == true ? 'selected' : "" }} value="selling_price">Selling Price</option>
                                        <option {{ $price_settings->vip_selling_price == true ? 'selected' : "" }}  value="vip_selling_price">VIP Selling Price</option>
                                        <option {{ $price_settings->vvip_selling_price == true ? 'selected' : "" }} value="vvip_selling_price">VVIP Selling Price</option>
                                        <option {{ $price_settings->executive_selling_price == true ? 'selected' : "" }} value="executive_selling_price">Executive Price</option>
                                    </select>
                                </div>

                                @if(userCanView("price_settings.update"))
                                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Save Changes</button>
                                @endif

                            </form>

                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
