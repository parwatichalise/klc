@extends('layouts.app')

@section('title', 'Add Question')

@section('content')
<div class="container mt-5">
    <!-- Success message -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Display errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <h1 class="my-4">Add New Question</h1>   
            <a href="{{ route('questions.index') }}" class="btn btn-success">Question List</a>
        </div>

        <div class="card-body">
            <!-- Form for creating a question -->
            <form action="{{ route('questions.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Quiz dropdown -->
                <div class="form-group mb-3">
                    <label for="quiz" class="form-label fw-bold">Quiz</label>
                    <select id="quiz" name="quiz" class="form-select" required>
                        <option value="" disabled selected>Select a Quiz</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}" {{ old('quiz') == $quiz->id ? 'selected' : '' }}>
                                {{ $quiz->heading }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="mb-3">
                    <label for="question_number" class="form-label">Question Number:</label>
                    <input type="number" class="form-control" id="question_number" name="question_number" value="{{ old('question_number') }}" required>
                </div>

                <div class="mb-3">
                    <label for="question_text" class="form-label">Question:</label>
                    <textarea class="form-control" id="question_text" name="question_text" required>{{ old('question_text') }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="sub_question" class="form-label">Sub Question:</label>
                    <input type="text" class="form-control" name="sub_question" id="sub_question" value="{{ old('sub_question') }}">
                </div>

                <div class="mb-3">
                    <label for="question_table" class="form-label">Question Table:</label>
                    <textarea class="form-control" name="question_table" id="question_table" rows="5">{{ old('question_table', $question->question_table) }}</textarea>
                </div>


                <div class="mb-3">
                    <label for="question_image" class="form-label">Upload Question Image</label>
                    <input type="file" class="form-control" id="question_image" name="question_image" accept="image/*">
                </div>

                <div class="mb-3">
                    <label for="question_sound" class="form-label">Upload Question Sound</label>
                    <input type="file" class="form-control" id="question_sound" name="question_sound" accept="audio/*">
                </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h4>Add Answer</h4>
            
            <div class="mb-3">
                <label for="input_type" class="form-label">Select Input Type</label>
                <select class="form-select" id="input_type" name="input_type" onchange="toggleInputFields()" required>
                    <option value="">Select Input Type</option>
                    <option value="text" {{ old('input_type') == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="image" {{ old('input_type') == 'image' ? 'selected' : '' }}>Image</option>
                    <option value="audio" {{ old('input_type') == 'audio' ? 'selected' : '' }}>Audio</option>
                </select>
            </div>

            <!-- Text options -->
            <div id="text-options" class="mb-3" style="display: none;">
                <label class="form-label">Text Options</label>
                <input type="text" class="form-control mb-2" name="option1" placeholder="Option 1" value="{{ old('option1') }}">
                <input type="text" class="form-control mb-2" name="option2" placeholder="Option 2" value="{{ old('option2') }}">
                <input type="text" class="form-control mb-2" name="option3" placeholder="Option 3" value="{{ old('option3') }}">
                <input type="text" class="form-control mb-2" name="option4" placeholder="Option 4" value="{{ old('option4') }}">
            </div>

            <!-- Image options -->
            <div id="image-options" class="mb-3" style="display: none;">
                <label for="image_option" class="form-label">Upload Image Options</label>
                <input type="file" class="form-control mb-2" name="image_option1" accept="image/*">
                <input type="file" class="form-control mb-2" name="image_option2" accept="image/*">
                <input type="file" class="form-control mb-2" name="image_option3" accept="image/*">
                <input type="file" class="form-control mb-2" name="image_option4" accept="image/*">
            </div>

            <!-- Audio options -->
            <div id="audio-options" class="mb-3" style="display: none;">
                <label for="audio_option" class="form-label">Upload Audio Options</label>
                <input type="file" class="form-control mb-2" name="audio_option1" accept="audio/*">
                <input type="file" class="form-control mb-2" name="audio_option2" accept="audio/*">
                <input type="file" class="form-control mb-2" name="audio_option3" accept="audio/*">
                <input type="file" class="form-control mb-2" name="audio_option4" accept="audio/*">
            </div>

            <!-- Correct option selection -->
            <div class="mb-3">
                <label for="correct_option" class="form-label">Select Correct Option</label>
                <select class="form-select" id="correct_option" name="correct_option" required>
                    <option value="option1" {{ old('correct_option') == 'option1' ? 'selected' : '' }}>Option 1</option>
                    <option value="option2" {{ old('correct_option') == 'option2' ? 'selected' : '' }}>Option 2</option>
                    <option value="option3" {{ old('correct_option') == 'option3' ? 'selected' : '' }}>Option 3</option>
                    <option value="option4" {{ old('correct_option') == 'option4' ? 'selected' : '' }}>Option 4</option>
                </select>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-success">Submit Question</button>
        </div>
    </div>
    </form>
</div>

<!-- Script to toggle answer fields based on input type -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#question_table'))
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    function toggleInputFields() {
    const inputType = document.getElementById('input_type').value;

    document.getElementById('text-options').style.display = 'none';
    document.getElementById('image-options').style.display = 'none';
    document.getElementById('audio-options').style.display = 'none';

    if (inputType === 'text') {
        document.getElementById('text-options').style.display = 'block';
        // Reset other fields
        document.querySelectorAll('#image-options input, #audio-options input').forEach(input => input.value = '');
    } else if (inputType === 'image') {
        document.getElementById('image-options').style.display = 'block';
        document.querySelectorAll('#text-options input, #audio-options input').forEach(input => input.value = '');
    } else if (inputType === 'audio') {
        document.getElementById('audio-options').style.display = 'block';
        document.querySelectorAll('#text-options input, #image-options input').forEach(input => input.value = '');
    }
}
    // Run toggleInputFields on page load to retain previous state
    document.addEventListener('DOMContentLoaded', function () {
        toggleInputFields();
    });
</script>
@endsection