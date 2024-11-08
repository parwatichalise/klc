@extends('layouts.app')

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
    <h1>Quiz List</h1>
    <a href="{{ route('quizzes.create') }}" class="btn btn-success">Add Quiz</a>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Photo</th>
                <th>Heading</th>
                <th>Sub Heading</th>
                <th>Price</th>
                <th>Time Duration</th>
                <th>Created By</th>
                <th>Active</th>
                <th>Tags</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($quizzes as $quiz)
            <tr>
                <td>{{ $quiz->id }}</td>
                <td>
                    <img src="{{ $quiz->photo ? asset('storage/' . $quiz->photo) : asset('images/default-quiz.png') }}" alt="{{ $quiz->heading }}" width="50">
                </td>
                <td>{{ $quiz->heading }}</td>
                <td>{{ $quiz->sub_heading }}</td>
                <td>{{ $quiz->price }}</td>
                <td>{{ $quiz->time_duration }}</td>
                <td>{{ $quiz->created_by }}</td>
                <td>{{ $quiz->active ? 'Yes' : 'No' }}</td>
                <td>
                    @foreach ($quiz->tags as $tag)
                        <span class="badge badge-secondary">{{ $tag->name }}</span>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $quizzes->links() }} 
</div>
@endsection
