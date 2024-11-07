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
    <h1>Edit Quiz</h1>

    <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="heading">Heading</label>
            <input type="text" name="heading" class="form-control" value="{{ old('heading', $quiz->heading) }}" required>
        </div>

        <div class="form-group">
            <label for="sub_heading">Sub Heading</label>
            <input type="text" name="sub_heading" class="form-control" value="{{ old('sub_heading', $quiz->sub_heading) }}">
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $quiz->price) }}" required>
        </div>

        <div class="form-group">
            <label for="time_duration">Time Duration</label>
            <input type="text" name="time_duration" class="form-control" value="{{ old('time_duration', $quiz->time_duration) }}">
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" name="photo" class="form-control">
            @if($quiz->photo)
                <img src="{{ asset($quiz->photo) }}" alt="Current Photo" width="100" class="mt-2">
            @endif
        </div>

        <div class="form-group">
            <label for="tags">Tags</label>
            <select name="tags[]" class="form-control" multiple>
                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ $quiz->tags->contains($tag) ? 'selected' : '' }}>{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="active">Active</label>
            <select name="active" class="form-control">
                <option value="1" {{ $quiz->active ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$quiz->active ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Update Quiz</button>
        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection