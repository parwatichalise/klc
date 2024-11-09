<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }}</title> <!-- Ensure $quiz->title is passed correctly -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
    .question-container {
        display: flex;
        gap: 20px;
        height: 270px;
    }

    .question-left, .question-right {
        width: 50%;
        overflow-y: auto;
        max-height: 280px;
        position: relative;
        padding: 20px;
    }

    .question-left::before {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        height: 100%;
        width: 2px;
        background-color: #ddd;
    }

    .question-right {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        flex-direction: column;
        padding-left: 10px; /* Adjust left padding to bring closer to the line */
    }

    .question-image {
        max-width: 100%;
        max-height: 150px;
        object-fit: contain;
        margin-top: 20px;
    }

    .answers {
       
        max-height: 200px;
        width: 100%;
    }

    .answers ol {
        list-style: none;
        padding: 0;
        width: 100%;
    }

    .answers ol li {
        display: flex;
        align-items: center;
        margin-bottom: 25px; /* Increased space between options */
        padding: 10px 10px 10px 5px; /* Reduced left padding */
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #f9f9f9;
        transition: background-color 0.3s;
    }

    .answers ol li:hover {
        background-color: #e6f7ff;
    }

    .answers ol li input[type="radio"] {
        margin-right: 10px;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.5;
    }
</style>


</head>
<body class="bg-gray-100">

<div class="container mx-auto p-4">
    <div class="bg-blue-500 text-white p-2 rounded flex justify-between items-center">
        <div class="text-lg font-bold">{{ $quiz->title }}</div> <!-- Correct quiz title rendering -->
        <div class="text-right">
            Time Remaining: <span id="timer">00:00</span>
        </div>    </div>

    <div class="mt-4 bg-white p-4 rounded shadow question-container">
        <!-- Left column for question number, text, and media -->
        <div class="question-left">
            <h2 class="text-lg font-bold mb-4">Question {{ $question->question_number }}:{{ $question->question_text }}</h2>
            @if ($question->question_image)
                <img src="{{ asset('storage/' . $question->question_image) }}" alt="Question Image" class="question-image">
            @elseif ($question->question_sound)
                <audio controls id="questionAudio" class="mt-4">
                    <source src="{{ asset('storage/' . $question->question_sound) }}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            @endif
        </div>


         <!-- Right column for answer options -->
         <div class="question-right">
            <form action="{{ route('questions.answer', ['quiz_id' => $quiz->id, 'question_id' => $question->id]) }}" method="POST" id="quizForm">
                @csrf
                <div class="answers">
                    @foreach($question->answers as $answer)
                        <div class="flex items-center mb-2">
                        <input type="radio" name="answer" value="{{ $answer->id }}" id="answer-{{ $answer->id }}"
                        {{ session("answer.{$quiz->id}.{$question->id}") == $answer->id ? 'checked' : '' }}
                       </div class="form-radio text-blue-500">

                            <label for="answer-{{ $answer->id }}" class="ml-2">
                                @if($answer->answer_type === 'text')
                                    <span>{{ $answer->answer_text }}</span>
                                @elseif($answer->answer_type === 'image')
                                    <img src="{{ asset('storage/' . $answer->answer_image) }}" alt="Answer Image" class="question-image">
                                @elseif($answer->answer_type === 'audio')
                                    <audio controls>
                                        <source src="{{ asset('storage/' . $answer->answer_sound) }}" type="audio/mpeg">
                                        Your browser does not support the audio element.
                                    </audio>
                                @endif
                            </label>
                        </div>
                    @endforeach    
                </div>
            </form>
        </div>
    </div>


<br>

<!-- Navigation Buttons -->
<div class="flex justify-between items-center mt-4" id="navigationButtons">
    @if ($prevQuestionNumber)
        <a href="{{ route('student.showQuestion', ['quiz_id' => $quiz->id, 'question_number' => $prevQuestionNumber]) }}" class="bg-blue-500 text-white p-2 rounded nav-button">Previous</a>
    @else
        <button class="bg-gray-500 text-white p-2 rounded cursor-not-allowed" disabled>Previous</button>
    @endif

    <a href="{{ route('start.exam', ['examTitle' => $quiz->heading]) }}" class="bg-blue-400 text-black p-2 rounded nav-button">
        Total Questions: {{ $totalQuestions }}
    </a>
    

    @if ($nextQuestionNumber)
        <button type="submit" form="quizForm" class="bg-blue-500 text-white p-2 rounded nav-button">Next</button>
    @else
        <button type="submit" form="quizForm" class="bg-blue-500 text-white p-2 rounded nav-button" onclick="return confirmSubmit()">Submit</button>
    @endif
</div>
<!-- Restart Exam Button -->
<a href="{{ route('start.exam', ['examTitle' => $quiz->heading]) }}" class="bg-blue-500 text-white p-2 rounded" onclick="localStorage.setItem('restartExam', true);">Restart Exam</a>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const audioElements = document.querySelectorAll("audio");
        const formInputs = document.querySelectorAll("#quizForm input, #quizForm button, .nav-button");

        function setDisabledState(state) {
            formInputs.forEach(input => input.classList.toggle("disabled", state));
        }

        audioElements.forEach(audioElement => {
            audioElement.addEventListener("play", () => setDisabledState(true));
            audioElement.addEventListener("pause", () => setDisabledState(false));
            audioElement.addEventListener("ended", () => setDisabledState(false));
        });
    });

    function confirmSubmit() {
        return confirm('You have completed the quiz. Are you sure you want to submit?');
    }
</script>

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