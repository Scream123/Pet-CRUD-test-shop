@extends('layouts.app')

@section('content')

    <h1>Create Product</h1>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif

    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Product Title</label>
            <input type="text" id="title" name="name" placeholder="Product Title" required>
        </div>

        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <select id="tags" name="tags[]" multiple required>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="description">Product Description</label>
            <textarea id="description" name="description" placeholder="Product Description" required></textarea>
        </div>

        <div class="form-group">
            <button type="submit">Create Product</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </form>
@endsection
