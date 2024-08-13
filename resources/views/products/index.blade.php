@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="list-group">
                    <a href="{{ route('web.products.create') }}" class="list-group-item list-group-item-action">Create New Product</a>
                    <a href="{{ route('web.categories.create') }}" class="list-group-item list-group-item-action">Add New Category</a>
                    <a href="{{ route('web.tags.create') }}" class="list-group-item list-group-item-action">Add New Tag</a>
                    <a href="{{ route('web.tags.index') }}" class="list-group-item list-group-item-action">List of Tags</a>
                    <a href="{{ route('web.categories.index') }}" class="list-group-item list-group-item-action">List of Categories</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <h1>Products</h1>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">{{ $message }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Tags</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->name }}</td>
                                <td>
                                    {{ $product->categories->first()->name ?? 'No Category' }}
                                </td>
                                <td>
                                    @if ($product->tags->isEmpty())
                                        No Tag
                                    @else
                                        @foreach ($product->tags as $tag)
                                            {{ $tag->name }}@if (!$loop->last)
                                                ,
                                            @endif
                                        @endforeach
                                    @endif
                                </td>
                                <td>{{ $product->description }}</td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Actions">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">Show</a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endsection
