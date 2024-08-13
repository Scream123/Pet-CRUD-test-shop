@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Tag</h1>

        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Editing: {{ $tag->name }}</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('tags.update', $tag->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Tag Name</label>
                        <input type="text" id="name" name="name" class="form-control"
                               value="{{ old('name', $tag->name) }}" placeholder="Tag Name" required>
                    </div>

                    <!-- Submit and Back Buttons -->
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">Update Tag</button>
                        <a href="{{ route('web.tags.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
