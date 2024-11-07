<!-- resources/views/student/viewresult.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="bg-blue-500 text-white text-center py-4">
        <h2 class="text-xl font-bold">Exam Ended - Your Result</h2>
    </div>

    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl">
            <div class="p-4 text-center">
                <h3 class="text-2xl font-semibold mb-4">Exam Result</h3>
                <div class="bg-white border rounded-lg shadow-sm p-4">
                <div class="text-right mb-4">
                    <p>Name: <strong>{{ $studentName }}</strong></p>
                    <p>Exam ID: <strong>{{ $examId }}</strong></p>
                    <p>Exam Title: <strong>{{ $examTitle }}</strong></p>
                </div>


                    <div class="grid grid-cols-2 gap-4 text-left">
    <p>Total Questions</p>
    <p class="text-right">{{ $totalQuestions }}</p>
    <p>Total Attempt</p>
    <p class="text-right">{{ $totalAttempt }}</p>
    <p>Total Correct</p>
    <p class="text-right">{{ $totalCorrect }}</p>
    <p>Your Percentage</p>
    <p class="text-right">{{ $percentage }}%</p>
</div>
<div class="mt-4 text-center">
                        <p class="text-lg font-bold text-{{ $percentage >= 50 ? 'green-500' : 'red-500' }}">
                            {{ $resultMessage }}
                        </p>
                    </div>

<!-- New Containers for Correct, Incorrect, and Unsolved -->
<div class="grid grid-cols-3 gap-4 mt-6">
    <div class="bg-green-100 border border-green-400 text-green-700 rounded-lg p-4 text-center">
        <h4 class="font-bold">Correct Questions</h4>
        <p class="text-2xl">{{ $totalCorrect }}</p>
        <ul class="list-disc list-inside mt-2">
            @foreach($correctQuestions as $question)
                <li>Question {{ $question }}</li>
            @endforeach
        </ul>
    </div>
    <div class="bg-red-100 border border-red-400 text-red-700 rounded-lg p-4 text-center">
        <h4 class="font-bold">Incorrect Questions</h4>
        <p class="text-2xl">{{ $totalIncorrect }}</p>
        <ul class="list-disc list-inside mt-2">
            @foreach($incorrectQuestions as $question)
                <li>Question {{ $question }}</li>
            @endforeach
        </ul>
    </div>
    <div class="bg-gray-100 border border-gray-400 text-gray-700 rounded-lg p-4 text-center">
        <h4 class="font-bold">Unsolved</h4>
        <p class="text-2xl">{{ $totalUnsolved }}</p>
        <ul class="list-disc list-inside mt-2">
            @foreach($unsolvedQuestions as $question)
                <li>Question {{ $question }}</li>
            @endforeach
        </ul>
    </div>
</div>

     </div>
    </div>
    <div class="flex justify-center gap-4 mt-4">
        <!-- viewresult.blade.php -->

        <a href="{{ route('student.result') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">RESULTS LIST</a>
    </div>
    </div>
</body>
</html>