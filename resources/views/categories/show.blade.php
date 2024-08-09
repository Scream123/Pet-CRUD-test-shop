@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Category Details</h1>

        <div class="card">
            <div class="card-header">
                <h2>{{ $category->name }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $category->name }}</p>
                <p><strong>Created At:</strong> {{ $category->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $category->updated_at }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this category?')">Delete
                    </button>
                </form>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
