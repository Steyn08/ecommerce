@extends('layouts.app')

@section('content')
<style>
    .card img {
        object-fit: cover;
        height: 100%;
    }

    .badge {
        margin-right: 5px;
    }
</style>
<div class="container mt-5">
    <h1>Product Details</h1>
    <div class="card">
        <div class="row g-0 p-3">
            <div class="col-md-4">
                <img src="{{ asset('/storage/'. $product->image_path) }}" alt="{{ $product->name }}" class="img-fluid rounded h-100">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text"><strong>Description:</strong> {{ $product->description }}</p>
                    <p class="card-text"><strong>Price:</strong> ₹{{ number_format($product->price, 2) }}</p>
                    <p class="card-text"><strong>Final Price:</strong> ₹{{ number_format($product->final_price, 2) }}</p>

                    <p class="card-text"><strong>Categories:</strong>
                        @foreach($product->categories as $category)
                        <span class="badge bg-primary mx-1">{{ $category->name }}</span>
                        @endforeach
                    </p>

                    <p class="card-text"><strong>Tags:</strong>
                        @foreach($product->tags as $tag)
                        <span class="badge bg-secondary mx-1">{{ $tag->name }}</span>
                        @endforeach
                    </p>

                    <p class="card-text"><strong>Suppliers:</strong>
                        @foreach($product->suppliers as $supplier)
                        <span class="badge bg-secondary mx-1">{{ $supplier->name }}</span>
                        @endforeach
                    </p>

                    <!-- <p class="card-text"><small class="text-muted">Last updated: {{ $product->updated_at->format('d M Y, h:i A') }}</small></p> -->
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('admin.products') }}" class="btn btn-secondary mt-3">Back to Products</a>
</div>
@endsection