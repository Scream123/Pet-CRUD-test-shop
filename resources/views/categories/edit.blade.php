@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Edit Category</h1>

        <div class="card">
            <div class="card-header">
                <h2>Editing: {{ $category->name }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('api.categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ old('name', $category->name) }}" placeholder="Category Name" required>
                    </div>

                    <!-- Submit and Back Buttons -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="{{ route('web.categories.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
