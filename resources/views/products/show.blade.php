@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Product Details</h1>

        <div class="card">
            <div class="card-header">
                <h2>{{ $product->name }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $product->name }}</p>

                <p><strong>Category:</strong>
                    {{ $product->categories->first()->name ?? 'No Category' }}
                </p>

                <p><strong>Tags:</strong>
                    @forelse ($product->tags as $tag)
                        {{ $tag->name }}@if (!$loop->last)
                            ,
                        @endif
                    @empty
                        No Tags
                    @endforelse
                </p>

                <p><strong>Slug:</strong> {{ $product->slug }}</p>
                <p><strong>Description:</strong> {{ $product->description }}</p>
                <p><strong>Created At:</strong> {{ $product->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $product->updated_at }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('web.products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this product?')">Delete
                    </button>
                </form>
                <a href="{{ route('web.products.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
