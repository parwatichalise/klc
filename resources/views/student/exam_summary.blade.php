<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Summary</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <!-- Top Bar -->
    <div class="bg-blue-500 text-white text-center py-4">
        <h2 class="text-xl font-bold">Exam Ended - Exam Summary</h2>
    </div>

    <!-- Content Container -->
    <div class="flex justify-center items-center min-h-screen px-4">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-4xl">
            <div class="p-4 text-center">
                <h3 class="text-2xl font-semibold mb-4">Congratulations, Exam Ended</h3>
                <div class="bg-white border rounded-lg shadow-sm p-4">
                <div class="text-right mb-4">
                    <p>Name: <strong>{{ $user->firstname }}</strong></p>
                    <p>Exam ID: <strong>{{ $user->exam_id }}</strong></p>
                    <p>Exam Title: <strong>{{ $examTitle }}</strong></p>
                </div>

                <div class="grid grid-cols-2 gap-4 text-left">
                    <p>Total Questions</p>
                    <p class="text-right">{{ $examDetails['totalQuestions'] }}</p>
                    <p>Total Attempt</p>
                    <p class="text-right">{{ $examDetails['totalAttempt'] }}</p>
                    <p>Total Correct</p>
                    <p class="text-right">{{ $examDetails['totalCorrect'] }}</p>
                    <p>Your Percentage</p>
                    <p class="text-right">{{ number_format($examDetails['percentage'], 2) }}%</p>
                </div>

                </div>
            </div>
            

            <div class="flex justify-center gap-4 mt-4">
                <a href="{{ route('student.viewresult', ['quiz_id' => $quiz_id]) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">VIEW RESULT</a>
                <a href="{{ route('student.result') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">RESULTS LIST</a>
                
            </div>

        </div>
    </div>
</body>
</html>
