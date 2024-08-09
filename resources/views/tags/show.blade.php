@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Tag Details</h1>

        <div class="card">
            <div class="card-header">
                <h2>{{ $tag->name }}</h2>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $tag->name }}</p>
                <p><strong>Created At:</strong> {{ $tag->created_at }}</p>
                <p><strong>Updated At:</strong> {{ $tag->updated_at }}</p>
            </div>
            <div class="card-footer">
                <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"
                            onclick="return confirm('Are you sure you want to delete this tag?')">Delete
                    </button>
                </form>
                <a href="{{ route('tags.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
@endsection
