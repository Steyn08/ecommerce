@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Main Content -->
    <main class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Products</h1>
            <a href="{{route('admin.products.add')}}" class="btn btn-primary">Add new</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm" id="productsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($products) && count($products) > 0)
                    @foreach ($products as $product)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>{{$product->name ?? '-'}}</td>
                        <td>{{$product->price?? '-'}}</td>
                        <td>
                            <div class="">
                                <a href="{{ route('admin.products.view', ['id' => $product->id]) }}" class="text-primary mx-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" class="text-warning mx-1">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.delete', ['id' => $product->id]) }}" method="POST" style="display:inline;" id="delete-form-{{$product->id}}">
                                    @csrf
                                    @method('post')
                                    <button type="button" class="border-0 p-0" onclick="confirmDelete({{ $product->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="4" class="text-center">No data</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </main>
</div>

@push('scripts')
<script>
    function confirmDelete(productId) {
        if (confirm('Are you sure you want to delete this product?')) {
            $('#delete-form-' + productId).submit();
        }
    }
    $(document).ready(function() {
        $('#productsTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthChange": true,
            "pageLength": 10,
            "columnDefs": [{
                "targets": 3,
                "orderable": false,
                "searchable": false
            }]
        });
    });
</script>
@endpush
@endsection