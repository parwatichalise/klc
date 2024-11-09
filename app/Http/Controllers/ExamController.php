<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Result;
use App\Models\Question;
use App\Models\User;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str; 

class ExamController extends Controller
{
    public function exam($examTitle)
    {
        $user = Auth::user();
        $examId = 'N/A';

        // Generate or fetch existing exam ID for the user
        if ($user) {
            if (!$user->exam_id) {
                $examId = $this->generateExamId($user);
                $user->exam_id = $examId;
                $user->save(); 
            } else {
                $examId = $user->exam_id;
            }
        }
        // Reset solved count to 0 and clear user_answers session upon login
        session(['solvedCount' => 0]);

        return view('student.exam', [
            'studentName' => $user ? $user->firstname : 'Unknown Student',
            'examTitle' => $examTitle, 
            'examId' => $examId,
        ]);
    }

    // Generates a unique exam ID
    private function generateExamId($user)
    {
        return 'EXAM-' . $user->id . strtoupper(Str::random(3));
    }

 // Function to start the exam and pass data to the view
 public function startExam($examTitle)
 {
     $user = Auth::user(); 
     $quiz = Quiz::where('heading', $examTitle)->first();
     if (!$quiz) {
         return redirect()->back()->withErrors(['message' => 'Quiz not found.']);
     }

     $quizId = $quiz->id;
     $questions = Question::where('quiz_id', $quizId)->get();

     $totalQuestions = $questions->count();
     $solvedQuestions = 0;         
     $unsolvedQuestions = $totalQuestions - $solvedQuestions;

     $packageName = "Your Package Name"; 
     $imageUrl = "path/to/image.png"; 
     $timeLimit = 3600; 
     $timeDuration = $quiz->time_duration;    
     return view('student.exam_start', [
         'studentName' => $user->firstname,
         'examTitle' => $examTitle, 
         'questions' => $questions,
         'totalQuestions' => $totalQuestions,  
         'solvedQuestions' => $solvedQuestions, 
         'unsolvedQuestions' => $unsolvedQuestions, 
         'packageName' => $packageName,
         'imageUrl' => $imageUrl,
         'timeLimit' => $timeLimit, 
         'timeDuration'=>$timeDuration,       
         'quizId' => $quizId,
         'quiz' => $quiz,
        ]);
 }


    // Function to show an individual question
    public function showQuestion($id)
    {
        // Fetch the question by ID
        $question = Question::findOrFail($id);

        // Return the view to display the question
        return view('student.show_question', compact('question'));
    }

    // Function to show the exam summary with the list of questions and status
    public function showExam($examTitle)
    {
        // Fetch the quiz details using the title
        $quiz = Quiz::where('heading', $examTitle)->first();

        // Redirect if the quiz is not found
        if (!$quiz) {
            return redirect()->back()->withErrors(['message' => 'Quiz not found.']);
        }

        // Define total questions based on the exam type (custom logic for 'Color Vision Test')
        $totalQuestions = strcasecmp(trim($examTitle), 'Color Vision Test') === 0 ? 20 : 40;

        // Assuming a way to track solved questions
        $solvedQuestions = 0; // Replace this with the actual count of solved questions
        $unsolvedQuestions = $totalQuestions - $solvedQuestions;

        // Pass the data to the exam view
        return view('student.show_exam', [
            'quiz' => $quiz,
            'totalQuestions' => $totalQuestions,
            'solvedQuestions' => $solvedQuestions,
            'unsolvedQuestions' => $unsolvedQuestions,
            'examTitle' => $examTitle,
        ]);
    }

    

    public function result(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'examTitle' => 'required|string',
            'score' => 'required|integer',
            'examId' => 'required|string',
        ]);
    
        $examTitle = $request->input('examTitle');
        $score = $request->input('score'); 
    
        // Get the logged-in user and save the result
        $user = Auth::user();
        if ($user) {
            $result = new Result();
            $result->user_id = $user->id;
            $result->exam_id = $request->input('examId'); 
            $result->score = $score;
            $result->save();
        }
    
        // Calculate percentage
        $totalQuestions = 50; // Replace with your logic to get total questions
        $percentage = ($score / $totalQuestions) * 100;
    
        // Redirect to the result page with score and exam title, using with() to flash data
        return redirect()->route('result', ['examTitle' => $examTitle])->with([
            'examTitle' => $examTitle,
            'percentage' => $percentage,
        ]);
   
    }
    
    public function showResult(Request $request)
    {
        // Retrieve the flashed data from the session
        $examTitle = session('examTitle');
        $percentage = session('percentage');
    
        // Check if data exists
        if (!$examTitle || !$percentage) {
            return redirect()->route('student.dashboard'); 
        }
    
        // Pass the data to the view
        return view('student.result', compact('examTitle', 'percentage'));
    }
    


}
