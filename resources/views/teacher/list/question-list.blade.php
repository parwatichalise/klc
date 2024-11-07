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
    
    <h2>Questions List</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <form id="search-form" action="{{ route('questions.index') }}" method="GET" class="d-flex w-50">
                <input type="text" id="search-input" name="search" class="form-control me-2" placeholder="Search Questions..." value="{{ request('search') }}">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>

        <div class="col-md-6 text-end">
            <div class="row mb-2">
                <div class="col-md-12 text-end">
                    <a href="{{ route('questions.create') }}" class="btn btn-success">Add New Question</a>
                </div>
            </div>

            <label for="quiz-select" class="me-2 fw-bold">Select Quiz:</label>
            <select id="quiz-select" class="form-select d-inline-block w-auto">
                <option value="">Select Quiz</option>
                @foreach($quizzes as $quiz)
                    <option value="{{ $quiz->id }}">{{ $quiz->heading }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="loading-spinner" class="spinner-border" style="display:none;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>

    <div id="questions-container" class="question-list" aria-live="polite">
        <p class="text-center text-muted">Select a quiz to view questions...</p>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#quiz-select').on('change', function() {
            var quizId = $(this).val(); 

            if (quizId) {
                $('#loading-spinner').show(); // Show spinner while loading
                $('#questions-container').html('<p>Loading questions...</p>');

                $.ajax({
                    url: `/questions/${quizId}/fetch`,
                    type: 'GET',
                    success: function(data) {
                        $('#loading-spinner').hide(); // Hide spinner
                        let questionsHtml = `<p>Total Questions: ${data.length}</p>`; // Initialize with total questions

                        if (data.length > 0) {
                            data.forEach(function(question, index) {
                                questionsHtml += '<div class="card mt-2">'; // Start card
                                questionsHtml += '<div class="card-body">';

                                // Display question number and question text
                                questionsHtml += `<h3 class="card-title" style="font-weight: bold; font-size: 1.25rem;">Question ${index + 1}:</h3>`;
                                questionsHtml += `<p style="margin-bottom: 0.5rem;">${question.question_text || '[Non-text question]'}</p>`;
                                questionsHtml += `<p>${question.question_table || '[No table data available]'}</p>`;
                                questionsHtml += '<hr>'; // Line after question table
                                
                                // Display image if available, else check for sound, otherwise show 'No media available'
                                if (question.question_image) {
                                    questionsHtml += `<img src="/storage/${question.question_image}" alt="Question Image" style="max-width: 100px;">`;
                                } else if (question.question_sound) {
                                    questionsHtml += `<audio controls><source src="/storage/${question.question_sound}" type="audio/mpeg"></audio>`;
                                } else {
                                    questionsHtml += '<p>No media available</p>';
                                }

                                // Display sub-questions
                                if (question.sub_questions && question.sub_questions.length > 0) {
                                    questionsHtml += '<h4>Sub Questions:</h4>';
                                    question.sub_questions.forEach(function(subQuestion) {
                                        questionsHtml += `<p>${subQuestion.text || '[No sub-question text]'} </p>`;
                                    });
                                }

                                // Display answer options
                                if (question.answers && question.answers.length > 0) {
                                    questionsHtml += '<div class="row">';
                                    question.answers.forEach(function(option, idx) {
                                        let optionContent = '';
                                        switch (option.answer_type) {
                                            case 'text':
                                                optionContent = option.answer_text;
                                                break;
                                            case 'image':
                                                optionContent = `<img src="/storage/${option.answer_image}" alt="Option Image" style="max-width: 100px;">`;
                                                break;
                                            case 'audio':
                                                optionContent = `<audio controls><source src="/storage/${option.answer_sound}" type="audio/mpeg"></audio>`;
                                                break;
                                            default:
                                                optionContent = '[Unknown option type]';
                                                break;
                                        }
                                        questionsHtml += `<div class="col-md-6 mb-2">Option ${idx + 1}: ${optionContent}</div>`;
                                    });
                                    questionsHtml += '</div>';
                                } else {
                                    questionsHtml += '<p class="text-muted">No answers available for this question.</p>';
                                }

                                // Display correct answer
                                questionsHtml += `<p><strong>Correct Answer:</strong> ${question.correct_answer || '[No correct answer]'}</p>`;

                                // Edit button
                                questionsHtml += `<a href="/questions/${question.id}/edit" class="btn btn-primary me-2">Edit</a>`;

                                // Delete form
                                questionsHtml += `
                                    <form action="/questions/${question.id}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this question?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>`;

                                questionsHtml += '</div></div>'; // End card
                            });
                        } else {
                            questionsHtml = '<p>No questions available for the selected quiz...</p>';
                        }

                        $('#questions-container').html(questionsHtml);
                    },
                    error: function(xhr, status, error) {
                        $('#loading-spinner').hide(); // Hide spinner on error
                        console.error('AJAX Error:', status, error);
                        $('#questions-container').html('<p>Error loading questions. Please try again.</p>');
                    }
                });
            } else {
                $('#questions-container').html('<p>Please select a quiz to view questions...</p>');
            }
        });
    });
</script>
@endsection
