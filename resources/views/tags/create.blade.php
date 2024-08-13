@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Add New Tag</h1>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mb-4">
                {{ $message }}
            </div>
        @endif

        <form action="{{ route('tags.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Tag Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Tag</button>
            <a href="{{ route('web.products.index') }}" class="btn btn-secondary">Back to List</a>

        </form>
    </div>
@endsection
