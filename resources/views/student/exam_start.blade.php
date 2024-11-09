<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .exam-header {
            background-color: #2196F3;
            color: white;
            padding: 15px;
            font-size: 18px;
        }
        .container-box {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .question-box {
            border: 1px solid #007bff;
            padding: 0; /* Remove padding */
            margin: 5px;
            text-align: center;
            cursor: pointer;
            border-radius: 5px;
            flex: 1 0 20%;
            display: flex; /* Flexbox to center content */
            justify-content: center;
            align-items: center;
            height: 60px; /* Set a fixed height */
        }

                .question-box a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            border-radius: inherit;
            text-decoration: none;
            outline: none; 
            border: none; 
        }

        .btn-success {
            background-color:#87CEFA; 
            color: black;
        }
        .question-box:hover {
            background-color: #90EE90;
        }
        .question-container {
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            border: 1px solid black;
        }
        .question-section-title {
            font-size: 20px;
            margin-bottom: 15px;
            text-align: center;
            color: #333;
        }
        .questions-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content:flex-start;
        gap: 10px; /* Add some space between the buttons */
    }

    .question-box {
        width: calc(10% - 10px); /* Adjust for 10 buttons in a row with gap */
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 10px;
    }

    /* Style the question buttons */
    .question-box a {
        width: 100%;
        height: 100%;
        padding: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        border-radius: 5px;
        text-align: center;
    }
        .time-remaining {
            font-weight: bold;
            color: #ff5722;
        }
        .btn-submit {
            margin-top: 10px;
        }
        .alert-info {
            font-size: 16px;
        }
        .no-underline {
            text-decoration: none;
            color: inherit;
        }


        

    </style>
</head>
<body>

    
<!-- Header -->
<div class="exam-header d-flex justify-content-between align-items-center">
    <h3>APS-KLC UBT Trail Exam</h3>
    <div class="user-name">
        <span>{{ auth()->user()->username }}</span> <!-- Display logged-in user's name -->
        <i class="fas fa-user-circle"></i>
    </div>
</div>
<br>
<h3 class="text-center">Exam Title: {{ $examTitle }}</h3>

<!-- Main Container -->
<div class="container container-box">

<!-- Info Bar -->
    <div class="row">
        <div class="col-md-3">
            <div class="alert alert-info text-center">
                Total Questions: <strong>{{ $totalQuestions }}</strong>
            </div>
        </div>

    <div class="col-md-3">
        <div class="alert alert-info text-center">
            Solved: <strong>{{ $questions->filter(function($question) {
                return DB::table('user_answers')->where('user_id', Auth::id())->where('question_id', $question->id)->exists();
            })->count() }}</strong>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-info text-center">
            Unsolved: <strong>{{ $totalQuestions - $questions->filter(function($question) {
                return DB::table('user_answers')->where('user_id', Auth::id())->where('question_id', $question->id)->exists();
            })->count() }}</strong>
        </div>
    </div>

    <div class="col-md-3">
        <div class="alert alert-info text-center">
            Time Remaining: <strong class="time-remaining" id="timer"></strong>
        </div>
    </div>
      
</div>

<div class="row">
<!-- Reading Questions Section -->
    <div class="col-md-6">
        <div class="question-container">
        <h5 class="question-section-title">Reading Questions</h5>
            <div class="questions-wrapper">
                @foreach ($questions as $question)
                    @if ($question->question_number <= 20)
                        @php
                            $isAnswered = DB::table('user_answers')
                                ->where('user_id', Auth::id())
                                ->where('question_id', $question->id)
                                ->exists();
                        @endphp
                        <a href="{{ route('student.showQuestion', ['quiz_id' => $question->quiz_id, 'question_number' => $question->question_number]) }}"
                           class="btn {{ $isAnswered ? 'btn-success' : 'btn-outline-secondary' }}">
                            {{ $question->question_number }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

<!-- Listening Questions Section -->
    <div class="col-md-6">
        <div class="question-container">
            <h5 class="question-section-title">Listening Questions</h5>
            @if (strcasecmp(trim($examTitle), 'Color Vision') === 0)
                <div class="d-flex justify-content-center align-items-center" style="height: 150px;">
                    No Listening Questions
                </div>
            @else
            <div class="questions-wrapper">
    @foreach ($questions as $question)
        @if ($question->question_number > 20)
            @php
                $isAnswered = DB::table('user_answers')
                    ->where('user_id', Auth::id())
                    ->where('question_id', $question->id)
                    ->exists();
            @endphp
            <a href="{{ route('student.showQuestion', ['quiz_id' => $question->quiz_id, 'question_number' => $question->question_number]) }}" 
               class="btn {{ $isAnswered ? 'btn-success' : 'btn-outline-secondary' }}">
                {{ $question->question_number }}
            </a>
        @endif
    @endforeach
</div>
            @endif
        </div>
    </div>
</div>


<!-- Submit Button -->
<div class="d-flex justify-content-end mt-3">
    <form method="GET" action="{{ url('/exam-summary/' . $quizId) }}">
        <button class="btn btn-primary btn-submit">Submit and Finish Exam</button>
    </form>
    </div>
</div>     

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the quiz time duration (in minutes) from the server
        let timeLimit = @json($timeDuration) * 60; // $timeDuration is the quiz time duration in minutes
        
        // Retrieve start time and remaining time from localStorage
        let startTime = localStorage.getItem('startTime');
        let timeRemaining = localStorage.getItem('timeRemaining');

        // Reset timer if this is a new attempt or if the start time is not saved
        if (!startTime || localStorage.getItem('restartExam')) {
            // Set start time and initialize time remaining
            startTime = Date.now();
            timeRemaining = timeLimit;
            localStorage.setItem('startTime', startTime);
            localStorage.setItem('timeRemaining', timeRemaining);

            // Remove restart flag if any
            localStorage.removeItem('restartExam');
        } else {
            // Calculate the elapsed time
            let elapsedTime = Math.floor((Date.now() - startTime) / 1000); // in seconds
            timeRemaining = timeRemaining - elapsedTime;

            // Ensure time remaining doesn't go below zero
            if (timeRemaining <= 0) {
                timeRemaining = 0;
            }

            // Update the remaining time in localStorage
            localStorage.setItem('timeRemaining', timeRemaining);
        }

        // Timer display element
        const timerElement = document.getElementById('timer');
        
        // Start the countdown timer
        const interval = setInterval(function () {
            const minutes = Math.floor(timeRemaining / 60); // minutes left
            const seconds = timeRemaining % 60; // seconds left

            // Display the time in MM:SS format
            timerElement.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            // When time is up, submit the form and redirect to exam summary
            if (timeRemaining <= 0) {
                clearInterval(interval); // stop the countdown
                timerElement.textContent = "Time's up!";

                // Automatically submit the quiz form
                document.getElementById("quizForm").submit();

                // Redirect to the exam summary page after 1 second delay
                setTimeout(function () {
                    window.location.href = "{{ route('exam.summary', ['quiz_id' => $quiz->id]) }}"; // Correct the route if needed
                }, 1000); // Delay for 1 second
            }

            // Decrease time remaining by 1 second
            timeRemaining--;

            // Save the updated time remaining in localStorage
            localStorage.setItem('timeRemaining', timeRemaining);
        }, 1000);
    });
</script>
</body>
</html>
