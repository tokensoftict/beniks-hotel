@extends('layouts.app')

@section('content')

    <div class="ui-container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <section class="panel">
                    <header class="panel-heading">
                        {{ $title }} {{ $cloth->cloth_name }}
                    </header>
                    <div class="panel-body">
                        @if(session('success'))
                            {!! alert_success(session('success')) !!}
                        @elseif(session('error'))
                            {!! alert_error(session('error')) !!}
                        @endif

                            <form action="{{ route('cloths.update', $cloth->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group">
                                    <label for="cloth_name">Cloth Name</label>
                                    <input type="text" name="cloth_name" id="cloth_name" class="form-control" value="{{ $cloth->cloth_name }}" required>
                                </div>

                                <div class="form-group">
                                    <label for="cloth_description">Cloth Description</label>
                                    <textarea name="cloth_description" id="cloth_description" class="form-control" required>{{ $cloth->cloth_description }}</textarea>
                                </div>

                                <hr>
                                <h4>Price Settings</h4>
                                <hr>

                                @foreach($services as $service)
                                    @php
                                        $price = \App\Models\ClothServiceMapper::where('laundry_service_id', $service->id)
                                        ->where('cloth_id', $cloth->id)->first();
                                        if($price) $price = $price->price;
                                        if(!$price) $price ="";
                                    @endphp
                                    <div class="form-group">
                                        <label for="cloth_name">{{ $service->laundry_service_name }}</label>
                                        <input type="number" value="{{ $price }}" name="prices[{{ $service->id }}]" id="{{ \Illuminate\Support\Str::slug($service->laundry_service_name) }}" class="form-control" required>
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>

                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection
