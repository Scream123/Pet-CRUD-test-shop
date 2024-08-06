@extends('layouts.app')

@section('content')
    <h1>Add New Tag</h1>
    @if ($message = Session::get('success'))
        <div>{{ $message }}</div>
    @endif

    <form action="{{ route('tags.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Tag Name:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <button type="submit">Add Tag</button>
    </form>
@endsection
