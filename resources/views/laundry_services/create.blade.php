@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ asset('bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css') }}">
    <link href="{{ asset('bower_components/datatables/media/css/jquery.dataTables.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-tabletools/css/dataTables.tableTools.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-colvis/css/dataTables.colVis.css') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-responsive/css/responsive.dataTables.scss') }}" rel="stylesheet">
    <link href="{{ asset('bower_components/datatables-scroller/css/scroller.dataTables.scss') }}" rel="stylesheet">
    <style>
        input {
            margin-bottom: 0 !important;
        }
    </style>
@endpush

@section('content')

    <div class="ui-container">
        <div class="row">
            <div class="col-md-6 col-lg-offset-3">
                <section class="panel">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="panel-body">
                            <form action="{{ route('services.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="laundry_service_name">Name </label>
                                    <input type="text" placeholder="Service Name" name="laundry_service_name" id="laundry_service_name" class="form-control" value="{{ old('laundry_service_name') }}" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Create</button>
                            </form>
                        </div>
                </section>
            </div>
        </div>
    </div>

@endsection


@push('js')

@endpush
