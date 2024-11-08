@extends('layouts.app')

@section('title', 'Edit Question')

@section('content')
<div class="container mt-5">
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

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <h1 class="my-4">Edit Question</h1>
            <a href="{{ route('questions.index') }}" class="btn btn-success">Question List</a>
        </div>

        <div class="card-body">
            <form action="{{ route('questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="quiz" class="form-label fw-bold">Quiz</label>
                    <select id="quiz" name="quiz" class="form-select" required>
                        <option value="" disabled>Select a Quiz</option>
                        @foreach($quizzes as $quiz)
                            <option value="{{ $quiz->id }}" {{ old('quiz', $question->quiz_id) == $quiz->id ? 'selected' : '' }}>
                                {{ $quiz->heading }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="question_number" class="form-label">Question Number:</label>
                    <input type="number" class="form-control" id="question_number" name="question_number" value="{{ old('question_number', $question->question_number) }}" required>
                </div>

                <div class="mb-3">
                    <label for="question_text" class="form-label">Question</label>
                    <textarea class="form-control" id="question_text" name="question_text" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label for="sub_question" class="form-label">Sub Questions:</label>
                    <textarea class="form-control" name="sub_question" id="sub_question" required>{{ old('sub_question', $question->sub_question) }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="question_table" class="form-label">Question Table</label>
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
                <h4>Edit Answer</h4>

                <div class="mb-3">
                    <label for="input_type" class="form-label">Select Input Type</label>
                    <select class="form-select" id="input_type" name="input_type" onchange="toggleInputFields()" required>
                        <option value="">Select Input Type</option>
                        <option value="text" {{ old('input_type', $question->input_type) == 'text' ? 'selected' : '' }}>Text</option>
                        <option value="image" {{ old('input_type', $question->input_type) == 'image' ? 'selected' : '' }}>Image</option>
                        <option value="audio" {{ old('input_type', $question->input_type) == 'audio' ? 'selected' : '' }}>Audio</option>
                    </select>
                </div>

                <div id="text-options" class="mb-3" style="display: {{ old('input_type', $question->input_type) == 'text' ? 'block' : 'none' }};">
                    <label class="form-label">Text Options</label>
                    @for ($i = 1; $i <= 4; $i++)
                        <input type="text" class="form-control mb-2" name="option{{ $i }}" placeholder="Option {{ $i }}" value="{{ old("option$i", $question->{"option$i"}) }}">
                    @endfor
                </div>

                <div id="image-options" class="mb-3" style="display: {{ old('input_type', $question->input_type) == 'image' ? 'block' : 'none' }};">
                    <label for="image_option" class="form-label">Upload Image Options</label>
                    @for ($i = 1; $i <= 4; $i++)
                        <input type="file" class="form-control mb-2" name="image_option{{ $i }}" accept="image/*">
                    @endfor
                </div>

                <div id="audio-options" class="mb-3" style="display: {{ old('input_type', $question->input_type) == 'audio' ? 'block' : 'none' }};">
                    <label for="audio_option" class="form-label">Upload Audio Options</label>
                    @for ($i = 1; $i <= 4; $i++)
                        <input type="file" class="form-control mb-2" name="audio_option{{ $i }}" accept="audio/*">
                    @endfor
                </div>

                <div class="mb-3">
                    <label for="correct_option" class="form-label">Select Correct Option</label>
                    <select class="form-select" id="correct_option" name="correct_option" required>
                        <option value="option1" {{ old('correct_option', $question->correct_option) == 'option1' ? 'selected' : '' }}>Option 1</option>
                        <option value="option2" {{ old('correct_option', $question->correct_option) == 'option2' ? 'selected' : '' }}>Option 2</option>
                        <option value="option3" {{ old('correct_option', $question->correct_option) == 'option3' ? 'selected' : '' }}>Option 3</option>
                        <option value="option4" {{ old('correct_option', $question->correct_option) == 'option4' ? 'selected' : '' }}>Option 4</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Update Question</button>
            </div>
        </div>
        </form>
    </div>
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
            } else if (inputType === 'image') {
                document.getElementById('image-options').style.display = 'block';
            } else if (inputType === 'audio') {
                document.getElementById('audio-options').style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            toggleInputFields();
        });
    </script>
@endsection
