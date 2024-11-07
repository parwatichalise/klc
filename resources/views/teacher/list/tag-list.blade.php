@extends('teacher.dashboard')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <h1 class="mb-4">Tag List</h1>
    <a href="{{ route('tags.create') }}" class="btn btn-success">Create New Tag</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td>{{ optional($tag->creator)->firstname . ' ' . optional($tag->creator)->lastname ?? 'N/A' }}</td> 
                <td>{{ optional($tag->updater)->firstname . ' ' . optional($tag->updater)->lastname ?? 'N/A' }}</td> 
                <td>
                    <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this tag?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{ $tags->links() }} 
</div>
@endsection