@extends('layouts.app')

@section('content')
<h1>Add New Category</h1>
@if ($message = Session::get('success'))
    <div class="alert alert-success">
        {{ $message }}
    </div>
@endif
<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <div>
        <label for="name">Category Name:</label>
        <input type="text" id="name" name="name" required>
    </div>
    <button type="submit">Add Category</button>
    <div class="form-group">
        <button type="submit">Create Product</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
    </div>
</form>
@endsection
