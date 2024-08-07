@extends('layouts.app')
@section('content')
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn">Create New Product</a>
    <a href="{{ route('categories.create') }}" class="btn">Add New Category</a>
    <a href="{{ route('tags.create') }}" class="btn">Add New Tag</a>

    @if ($message = Session::get('success'))
        <div>{{ $message }}</div>
    @endif

    <table>
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
                            {{ $tag->name  ?? 'No Tag' }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    @endif

                </td>
                <td>{{ $product->description }}</td>
                <td>
                    <a href="{{ route('products.show', $product->id) }}">Show</a>
                    <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
