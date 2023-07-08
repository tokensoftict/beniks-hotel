@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
@endpush


@section('content')
    <div class="ui-container">
        <div class="row">
            <div class=" col-md-offset-1 col-md-6">
                @if(session('success'))
                    {!! alert_success(session('success')) !!}
                @elseif(session('error'))
                    {!! alert_error(session('error')) !!}
                @endif
                <form action="" method="post">
                    {{ csrf_field() }}
                    <section class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Select Stock to Adjust</label>
                                        <select class="form-control  select-customer"  name="select_stock" id="customer_id">
                                            <option>Select Stock</option>
                                            @foreach($stocks as $stock)
                                                <option {{ $select_stock == $stock->id ? 'selected' : '' }} value="{{ $stock->id }}">{{ $stock->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <br/>
                                    <input class="btn btn-info btn-sm" style="margin-top: 5px;" type="submit" name="save" value="Fetch Stock">
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
            </div>

            @if(isset($convert_stock))
                <div class="col-md-6 col-md-offset-1">
                    <form action="" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="stock_id" value="{{ $convert_stock->id }}"/>
                        <section class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4>Stock Information</h4>
                                        <div class="form-group">
                                            <label style="font-size: 12px">Name</label><br/>
                                            <label style="font-size: 15px">{{ $convert_stock->name }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 12px">Available Quantity</label><br/>
                                            <label style="font-size: 15px">{{ $convert_stock->available_quantity }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 12px">Selling Price</label><br/>
                                            <label style="font-size: 15px">{{ number_format($convert_stock->selling_price,2) }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 12px">Cost Price</label><br/>
                                            <label style="font-size: 15px">{{ number_format($convert_stock->cost_price,2) }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 12px">Name</label><br/>
                                            <label style="font-size: 15px">{{ $convert_stock->name }}</label>
                                        </div>
                                        <div class="form-group">
                                            <label style="font-size: 12px">Current Quantity</label><br/>
                                            <label style="font-size: 15px;">{{ $convert_stock->getAllAvailableQuantity()[getActiveStore()->id] }}</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">

                                        <div class="form-group" >
                                            <label style="font-size: 12px">{{ getActiveStore()->name  }} Department  Quantity</label><br/>
                                            <input type="number" value="{{ $convert_stock->getAllAvailableQuantity()[getActiveStore()->id] }}"  class="form-control" name="{{ getActiveStore()->packed_column }}" placeholder="New Packed Quantity"/>
                                        </div>
                                        <!--
                                        <div class="form-group">
                                            <label style="font-size: 12px"> Enter New Yard Quantity</label><br/>

                                        </div>

                        -->

                                        <input type="hidden" value="{{ $convert_stock->available_yard_quantity }}"  class="form-control" name="yard_qty" placeholder="New Yard Quantity"/>

                                        <input class="btn btn-info btn-block btn-lg" type="submit" name="save" value="Adjust">

                                    </div>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>
            @endif

        </div>
    </div>
@endsection

@push('js')
    <script data-turbolinks-eval="false" data-turbo-eval="false" src="{{ asset('bower_components/select2/dist/js/select2.min.js') }}"></script>
    <script  src="{{ asset('assets/js/init-select2.js') }}"></script>
@endpush

