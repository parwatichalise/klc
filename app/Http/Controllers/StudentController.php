<?php

namespace App\Http\Controllers;
use App\Models\Answer;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Quiz;
use App\Models\Package;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class StudentController extends Controller
{
    public function showDashboard()
    {
        $user = Auth::user();
        $quizzes = Quiz::with('tags')->paginate(10);

            
        if ($user) {
            $userAnswers = DB::table('user_answers')->where('user_id', $user->id)->pluck('answer_text', 'question_id');
            session(['user_answers' => $userAnswers]);
        }

        if ($user) {
            return view('student.dashboard', [
                'studentName' => $user->firstname,
                'quizzes' => $quizzes
            ]);
        } else {
            return view('student.dashboard', [
                'studentName' => 'Unknown Student',
                'quizzes' => []
            ]);
        }
    }


    public function showQuestion($quiz_id, $question_number)
    {
        $quiz = Quiz::findOrFail($quiz_id);
        $question = Question::with('options')
            ->where('quiz_id', $quiz_id)
            ->where('question_number', $question_number)
            ->firstOrFail();

        $userAnswer = DB::table('user_answers')
            ->where('user_id', Auth::id())
            ->where('question_id', $question->id)
            ->first();

        $userSelectedAnswer = session("answer.{$quiz_id}.{$question->id}");
        $totalTime = 1200;
        $timeRemaining = $totalTime;
        $totalQuestions = Question::where('quiz_id', $quiz_id)->count();
        $nextQuestionNumber = $question_number < $totalQuestions ? $question_number + 1 : null;
        $prevQuestionNumber = $question_number > 1 ? $question_number - 1 : null;

        if (!session()->has("quiz_attempted_{$quiz_id}")) {
            $solvedCount = 0;
            $unsolvedCount = $totalQuestions;
            session(["quiz_attempted_{$quiz_id}" => true]);
        } else {
            $solvedCount = DB::table('user_answers')
                ->where('user_id', Auth::id())
                ->whereNotNull('answer_text')
                ->whereIn('question_id', Question::where('quiz_id', $quiz_id)->pluck('id'))
                ->count();
            $unsolvedCount = $totalQuestions - $solvedCount;
        }

        return view('student.question_detail', [
            'quiz' => $quiz,
            'question' => $question,
            'options' => $question->options,
            'userSelectedAnswer' => $userSelectedAnswer,
            'userAnswer' => $userAnswer,
            'timeRemaining' => $timeRemaining,
            'nextQuestionNumber' => $nextQuestionNumber,
            'prevQuestionNumber' => $prevQuestionNumber,
            'solvedCount' => $solvedCount,
            'unsolvedCount' => $unsolvedCount,
            'totalQuestions' => $totalQuestions,
        ]);
    }

    
    public function saveAnswer(Request $request, $quiz_id, $question_id)
    {
        $request->validate([
            'answer' => 'nullable|exists:options,id',
        ]);
    
        session(["answer.{$quiz_id}.{$question_id}" => $request->input('answer')]);
    
        $question = Question::findOrFail($question_id);
        $answerText = $request->input('answer') ? 
            $question->options()->where('id', $request->input('answer'))->value('answer_text') : 
            null;
    
        // Get the media fields for the question and answer
        $questionImage = $question->question_image;
        $questionSound = $question->question_sound;
        $answerImage = $request->input('answer') ? $question->options()->where('id', $request->input('answer'))->value('answer_image') : null;
        $answerSound = $request->input('answer') ? $question->options()->where('id', $request->input('answer'))->value('answer_sound') : null;
    
        // Insert or update the answer in the user_answers table 
        DB::table('user_answers')->updateOrInsert(
            [
                'user_id' => Auth::id(),
                'question_id' => $question->id,
                'quiz_id' => $quiz_id, 
            ],
            [
                'question_number' => $question->question_number,
                'question_text' => $question->question_text,
                'answer_text' => $answerText,
                'question_image' => $questionImage,
                'question_sound' => $questionSound,
                'answer_image' => $answerImage,
                'answer_sound' => $answerSound,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    
        $totalQuestions = Question::where('quiz_id', $quiz_id)->count();
    
        if ($question->question_number < $totalQuestions) {
            return redirect()->route('student.showQuestion', [
                'quiz_id' => $quiz_id,
                'question_number' => $question->question_number + 1
            ]);
        } else {
            return redirect()->route('student.showQuestion', [
                'quiz_id' => $quiz_id,
                'question_number' => $question->question_number
            ]);
        }
    }
    

    public function showExamSummary($quiz_id)
    {
        $user = Auth::user();
        $examId = $user->exam_id;
        $studentName = $user->username;
    
        // Fetch the user's answers for the quiz
        $userAnswers = DB::table('user_answers')
            ->where('user_id', $user->id)
            ->where('quiz_id', $quiz_id)
            ->get();
    
        // Fetch the total number of questions for the quiz
        $totalQuestions = DB::table('questions')->where('quiz_id', $quiz_id)->count();
    
        // Calculate total attempts
        $totalAttempt = $userAnswers->filter(function ($answer) {
            return $answer->answer_text !== null || $answer->answer_image !== null || $answer->answer_sound !== null;
        })->count();
    
        // Calculate total correct answers
        $totalCorrect = 0;
        foreach ($userAnswers as $userAnswer) {
            $answer = Answer::where('question_id', $userAnswer->question_id)
                ->where(function($query) use ($userAnswer) {
                    $query->where('answer_text', $userAnswer->answer_text)
                          ->orWhere('answer_image', $userAnswer->answer_image)
                          ->orWhere('answer_sound', $userAnswer->answer_sound);
                })
                ->where('is_correct', true)
                ->first();
    
            if ($answer) {
                $totalCorrect++;
            }
        }
    
        // Prepare the exam details array
        $examDetails = [
            'totalQuestions' => $totalQuestions,
            'totalAttempt' => $totalAttempt,
            'totalCorrect' => $totalCorrect,
            'percentage' => $totalQuestions > 0 ? ($totalCorrect / $totalQuestions) * 100 : 0.00,
        ];
    
        // Fetch the exam title (using 'heading' instead of 'title')
        $quiz = Quiz::find($quiz_id);
        $examTitle = $quiz ? $quiz->heading : 'Exam';
    
        return view('student.exam_summary', compact('user', 'examDetails', 'examTitle', 'quiz_id'));
    }
    
    public function studentDashboard()
    {
        $userId = Auth::id();
        $studentName = Auth::user()->username;
        $quizzes = Quiz::with('tags')->get();
        $payments = Payment::where('user_id', $userId)->where('is_active', 1)->get();

        return view('student.dashboard', compact('quizzes', 'payments', 'studentName'));
    }

    public function showViews($packageName, Request $request)
{
    $imageUrl = $request->query('imageUrl', asset('images/default.png')); // Provide a default image path
    $student = Student::first();
    $user = Auth::user();

    // Fetch the package by name to get its ID
    $package = Package::where('name', $packageName)->first();

    // Check if the package exists
    if (!$package) {
        return redirect()->back()->withErrors(['Package not found.']);
    }

    // Fetch quizzes related to the package
    $packageQuizzes = Quiz::where('package_id', $package->id)->get();

    return view('student.examview', [
        'studentName' => $user ? $user->firstname : 'Unknown Student',
        'packageName' => $package->name,
        'imageUrl' => $imageUrl,
        'packageQuizzes' => $packageQuizzes,
        'packagePrice' => $package->price,
        'package' => $package,
    ]);
}

public function showAvailableExams(Request $request)    
{
    $student = auth::user();

    // Check if the user is authenticated
    if (!$student) {
        return redirect()->route('login'); // Redirect if not authenticated
    }

    $studentName = $student->firstname; 
    $packageId = $request->query('package_id'); 

    // Fetch active quizzes associated with the package and ensure the package_id is not null
    $quizzes = Quiz::where('package_id', '!=', null)
                   ->active() // Use the scope here
                   ->with('tags')
                   ->paginate(10);

    return view('student.available_exams', compact('studentName', 'quizzes'));
}

}