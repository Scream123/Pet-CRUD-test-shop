@extends('layouts.app')

@section('content')
<h1>Add New Category</h1>
@if ($message = Session::get('success'))
<div>{{ $message }}</div>
@endif

<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <button type="submit">Add Category</button>
</form>
@endsection
