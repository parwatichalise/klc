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
    <h1>Add Quiz</h1>
    
    <form action="{{ route('quizzes.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="package_id">Select Package:</label>
            <select name="package_id" id="package_id" class="form-control">
                <option value="">None</option>
                @foreach ($packages as $package)
                    <option value="{{ $package->id }}" {{ (isset($quiz) && $quiz->package_id == $package->id) ? 'selected' : '' }}>
                        {{ $package->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo">
        </div>
        
        <div class="form-group">
            <label for="heading">Heading:</label>
            <input type="text" class="form-control" id="heading" name="heading" required>
        </div>

        <div class="form-group">
            <label for="sub_heading">Sub Heading:</label>
            <input type="text" class="form-control" id="sub_heading" name="sub_heading">
        </div>
        
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" class="form-control" id="price" name="price" step="0.01">
        </div>

        <div class="form-group">
            <label for="time_duration">Time Duration (in minutes):</label>
            <input type="number" class="form-control" id="time_duration" name="time_duration" required min="1" step="1">
        </div>  

        <div class="form-group">
            <label for="tags">Tags:</label>
            <select name="tags[]" class="form-control" id="tags" multiple required>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="active">Active:</label>
            <select name="active" class="form-control" id="active">
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Submit Quiz</button>
    </form>
</div>
@endsection