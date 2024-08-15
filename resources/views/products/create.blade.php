@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Create Product</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        <form action="{{ route('api.products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Product Title</label>
                <input type="text" id="title" name="name" class="form-control" placeholder="Product Title" required>
            </div>

            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select id="category" name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="tags" class="form-label">Tags</label>
                <select id="tags" name="tags[]" class="form-select" multiple required>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Product Description</label>
                <textarea id="description" name="description" class="form-control" placeholder="Product Description" required></textarea>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Create Product</button>
                <a href="{{ route('web.products.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>

@endsection
