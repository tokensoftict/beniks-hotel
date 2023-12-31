@extends('layouts.app')

@push('css')
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
@endpush


@section('content')
    <div class="ui-container">
        <div class="row">
            <div class="col-md-12">
                <section class="panel">
                    <header class="panel-heading panel-border">
                        {{ $title }}
                        @if(userCanView('stock.create'))
                            <span class="tools pull-right">
                                            <a  href="{{ route('stock.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Stock</a>
                            </span>
                        @endif
                    </header>
                    <div class="panel-body">
                        @if(session('success'))
                            {!! alert_success(session('success')) !!}
                        @elseif(session('error'))
                            {!! alert_error(session('error')) !!}
                        @endif
                        <table class="table {{ config('app.store') == "inventory" ? "" : 'convert-data-table' }} table-bordered table-responsive table-striped" style="font-size: 12px">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Product Type</th>
                                <th>Category</th>
                                <th>Manufacturer</th>
                                <th>Selling Price</th>
                                <th>Cost Price</th>
                                @if(config('app.store') === "hotel")
                                    <th>Vip Price</th>
                                    <th>VVIP Price</th>
                                    <th>Executive Price</th>
                                @else
                                    <th>Yard Selling Price</th>
                                    <th>Yard Cost Price</th>
                                @endif
                                <th>Last Updated</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($stocks as $stock)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $stock->name }}</td>
                                    <td>{{ $stock->type }}</td>
                                    <td>{{ $stock->product_category ?  $stock->product_category->name : "No Category" }}</td>
                                    <td>{{ $stock->manufacturer ?  $stock->manufacturer->name : "No Manufacturer" }}</td>
                                    <td>{{ number_format($stock->selling_price,2) }}</td>
                                    <td>{{ number_format($stock->cost_price,2) }}</td>
                                    @if(config('app.store') === "hotel")
                                        <td>{{ number_format($stock->vip_selling_price,2) }}</td>
                                        <td>{{ number_format($stock->vvip_selling_price,2) }}</td>
                                        <td>{{ number_format($stock->executive_selling_price,2) }}</td>
                                    @else
                                        <td>{{ number_format($stock->yard_selling_price,2) }}</td>
                                        <td>{{ number_format($stock->yard_cost_price,2) }}</td>
                                    @endif
                                    <td>{{ $stock->last_updated ? $stock->last_updated->name  : ""}}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button data-toggle="dropdown" class="btn btn-success dropdown-toggle btn-xs" type="button" aria-expanded="false">Action <span class="caret"></span></button>
                                            <ul role="menu" class="dropdown-menu">
                                                @if(userCanView('stock.edit'))
                                                    <li><a href="{{ route('stock.edit',$stock->id) }}">Edit</a></li>
                                                @endif
                                                @if(userCanView('stock.toggle'))
                                                    <li><a href="{{ route('stock.toggle',$stock->id) }}">{{ $stock->status == 0 ? 'Enabled' : 'Disabled' }}</a></li>
                                                @endif
                                                @if(userCanView('stock.stock_report'))
                                                    <li><a href="{{ route('stock.stock_report',$stock->id) }}">Product Report</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if(config('app.store') == "inventory")
                            {!! $stocks->links() !!}
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection


@push('js')
    <script data-turbolinks-eval="false" data-turbo-eval="false"  src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script data-turbolinks-eval="false" data-turbo-eval="false"  src="{{ asset('bower_components/datatables-tabletools/js/dataTables.tableTools.js') }}"></script>
    <script data-turbolinks-eval="false" data-turbo-eval="false"  src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script data-turbolinks-eval="false" data-turbo-eval="false"  src="{{ asset('bower_components/datatables-colvis/js/dataTables.colVis.js') }}"></script>
    <script data-turbolinks-eval="false" data-turbo-eval="false"  src="{{ asset('bower_components/datatables-responsive/js/dataTables.responsive.js') }}"></script>
    <script data-turbolinks-eval="false" data-turbo-eval="false"  src="{{ asset('bower_components/datatables-scroller/js/dataTables.scroller.js') }}"></script>
    <script src="{{ asset('assets/js/init-datatables.js') }}"></script>
@endpush
