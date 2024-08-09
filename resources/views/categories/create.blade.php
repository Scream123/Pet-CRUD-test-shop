@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Add New Category</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Category Name:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Add Category</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </form>
    </div>
@endsection
