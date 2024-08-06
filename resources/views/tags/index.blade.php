@extends('layouts.app')

@section('content')
    <h1>Tags</h1>
    <a href="{{ route('tags.create') }}">Add New Tag</a>

    @if ($message = Session::get('success'))
        <div>{{ $message }}</div>
    @endif

    <table>
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
                    <!-- Здесь можно добавить действия для редактирования и удаления -->
                    <a href="{{ route('tags.edit', $tag->id) }}">Edit</a>
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
