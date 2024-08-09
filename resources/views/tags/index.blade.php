@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tags</h1>

        <div class="mb-3">
            <a href="{{ route('tags.create') }}" class="btn btn-primary">Add New Tag</a>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mb-3">
                {{ $message }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tags as $tag)
                <tr>
                    <td>{{ $tag->id }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>
                        <a href="{{ route('tags.show', $tag->id) }}" class="btn btn-info btn-sm">Show</a>
                        <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
