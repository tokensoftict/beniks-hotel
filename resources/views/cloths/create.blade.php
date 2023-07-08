@extends('layouts.app')

@section('content')

    <div class="ui-container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
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

                        <form action="{{ route('cloths.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="cloth_name">Cloth Name</label>
                                <input type="text" name="cloth_name" id="cloth_name" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="cloth_description">Cloth Description</label>
                                <textarea name="cloth_description" id="cloth_description" class="form-control" required></textarea>
                            </div>

                            <hr>
                            <h4>Price Settings</h4>
                            <hr>

                            @foreach($services as $service)
                                <div class="form-group">
                                    <label for="cloth_name">{{ $service->laundry_service_name }}</label>
                                    <input type="number" name="prices[{{ $service->id }}]" id="{{ \Illuminate\Support\Str::slug($service->laundry_service_name) }}" class="form-control" required>
                                </div>
                            @endforeach

                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>

                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
