@extends('layouts.app')

@section('content')
<div class="container">
    <main class="col-12">
        <div class="container mt-5">
            <h1>{{ isset($product) ? 'Edit Product' : 'Add Product' }}</h1>

            <form action="{{ isset($product) ? route('admin.products.update', $product->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}">
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $product->price ?? '') }}">
                    @error('price')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @if(isset($product) && $product->image_path)
                    <div class="mt-2 d-flex flex-column align-items-start">
                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="Product Image" width="100" height="100">

                        <!-- <form action="{{ route('admin.products', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete Image</button>
                        </form> -->
                    </div>

                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Categories</label>
                    @foreach($categories as $category)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="categories[]" id="category_{{$category->value}}" value="{{ $category->value }}"
                            {{ isset($product) && $product->categories->contains($category->value) ? 'checked' : (is_array(old('categories')) && in_array($category->value, old('categories')) ? 'checked' : '') }}>
                        <label class="form-check-label" for="category_{{$category->value}}">{{ $category->label }}</label>

                    </div>
                    @endforeach
                    @error('categories')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Tags</label>
                    <div id="tags-wrapper">
                        @if(isset($product) && $product->tags->isNotEmpty())
                        @foreach($product->tags as $tag)
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="tags[]" value="{{ old('tags.' . $loop->index, $tag->name) }}" placeholder="Tag">
                            <button type="button" class="btn btn-danger remove-tag">Delete</button>
                        </div>
                        @endforeach
                        @elseif(old('tags'))
                        @foreach(old('tags') as $tag)
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="tags[]" value="{{ $tag }}" placeholder="Tag">
                            <button type="button" class="btn btn-danger remove-tag">Delete</button>
                        </div>
                        @endforeach
                        @else
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="tags[]" placeholder="Tag">
                            <button type="button" class="btn btn-danger remove-tag">Delete</button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-tag">Add More Tags</button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Suppliers</label>
                    <div id="suppliers-wrapper">
                        @if(isset($product) && $product->suppliers->isNotEmpty())
                        @foreach($product->suppliers as $supplier)
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="suppliers[]" value="{{ old('suppliers.' . $loop->index, $supplier->name) }}" placeholder="Supplier Name">
                            <button type="button" class="btn btn-danger remove-supplier">Delete</button>
                        </div>
                        @endforeach
                        @elseif(old('suppliers'))
                        @foreach(old('suppliers') as $supplier)
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="suppliers[]" value="{{ $supplier }}" placeholder="Supplier Name">
                            <button type="button" class="btn btn-danger remove-supplier">Delete</button>
                        </div>
                        @endforeach
                        @else
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" name="suppliers[]" placeholder="Supplier Name">
                            <button type="button" class="btn btn-danger remove-supplier">Delete</button>
                        </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary" id="add-supplier">Add More</button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Profit Margin</label>
                    <div class="form-check">
                        <input type="radio" class="form-check-input profit-type" name="profit_type" id="percentage" value="percentage" {{ old('profit_type', $product->profit_type ?? '1') === '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="percentage">Percentage (%)</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" class="form-check-input profit-type" name="profit_type" id="amount" value="amount" {{ old('profit_type', $product->profit_type ?? '2') === '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="amount">Fixed Amount</label>
                    </div>
                    <input type="number" class="form-control mt-2" id="profit_margin" name="profit_margin" value="{{ old('profit_margin', $product->profit_margin ?? '') }}" placeholder="Enter profit margin">
                    @error('profit_margin')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="final_price" class="form-label">Final Price</label>
                    <input type="text" class="form-control" id="final_price" name="final_price" value="{{ old('final_price', $product->final_price ?? '') }}" readonly>
                </div>

                <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update Product' : 'Add Product' }}</button>
            </form>
        </div>
    </main>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // $('.remove-tag').first().hide();
        // $('.remove-supplier').first().hide();

        $(document).on('input change', '#profit_margin, #price, .profit-type', function() {
            const price = parseFloat($('#price').val() || 0);
            const profitMargin = parseFloat($('#profit_margin').val() || 0);
            const profitType = $('input[name="profit_type"]:checked').val();
            let finalPrice;

            if (profitType === 'percentage') {
                finalPrice = price + (price * profitMargin / 100);
            } else {
                finalPrice = price + profitMargin;
            }

            $('#final_price').val(finalPrice.toFixed(2));
        });

        $(document).on('click', '#add-tag', function() {
            $('#tags-wrapper').append(`
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="tags[]" placeholder="Tag">
                <button type="button" class="btn btn-danger remove-tag">Delete</button>
            </div>
        `);
        });

        $(document).on('click', '#add-supplier', function() {
            $('#suppliers-wrapper').append(`
            <div class="input-group mb-2">
                <input type="text" class="form-control" name="suppliers[]" placeholder="Supplier Name">
                <button type="button" class="btn btn-danger remove-supplier">Delete</button>
            </div>
        `);
        });

        $(document).on('click', '.remove-supplier', function() {
            $(this).closest('.input-group').remove();
        });
        $(document).on('click', '.remove-tag', function() {
            $(this).closest('.input-group').remove();
        });
    });
</script>
@endpush
@endsection