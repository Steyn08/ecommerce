@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if(!isset($products) || empty($products))
        <div class="col-12">
            <div class="alert alert-warning text-center" role="alert">
                No products found. Please check back later.
            </div>
        </div>
        @else
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card" style="width: 18rem;">
                <img class="card-img-top" src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }} Image" height="200">

                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>

                    <p class="card-text">
                        {{ Str::limit($product->description, 100, '...') }}
                    </p>

                    <p class="card-text">
                        <strong>Categories:</strong>
                        @foreach($product->categories as $category)
                        <span class="badge bg-secondary mx-1">{{ $category->name }}</span>
                        @endforeach
                    </p>

                    <p class="card-text">
                        <strong>Tags:</strong>
                        @foreach($product->tags as $tag)
                        <span class="badge bg-info mx-1">{{ $tag->name }}</span>
                        @endforeach
                    </p>

                    <p class="card-text font-weight-bold">
                        ${{ number_format($product->price, 2) }}
                    </p>

                    <a href=javascript:void(0) class="btn btn-primary">Buy now</a>
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection