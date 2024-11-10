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

        if ($user) {
            if (!$user->exam_id) {
                $examId = $this->generateExamId($user);
                $user->exam_id = $examId;
                $user->save(); 
            } else {
                $examId = $user->exam_id;
            }
        }
        session(['solvedCount' => 0]);

        return view('student.exam', [
            'studentName' => $user ? $user->firstname : 'Unknown Student',
            'examTitle' => $examTitle, 
            'examId' => $examId,
        ]);
    }

    private function generateExamId($user)
    {
        return 'EXAM-' . $user->id . strtoupper(Str::random(3));
    }

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

    public function showQuestion($id)
    {
        $question = Question::findOrFail($id);

        return view('student.show_question', compact('question'));
    }

    public function showExam($examTitle)
    {
        $quiz = Quiz::where('heading', $examTitle)->first();

        if (!$quiz) {
            return redirect()->back()->withErrors(['message' => 'Quiz not found.']);
        }

        $totalQuestions = strcasecmp(trim($examTitle), 'Color Vision Test') === 0 ? 20 : 40;

        $solvedQuestions = 0; 
        $unsolvedQuestions = $totalQuestions - $solvedQuestions;

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
        $request->validate([
            'examTitle' => 'required|string',
            'score' => 'required|integer',
            'examId' => 'required|string',
        ]);
    
        $examTitle = $request->input('examTitle');
        $score = $request->input('score'); 
    
        $user = Auth::user();
        if ($user) {
            $result = new Result();
            $result->user_id = $user->id;
            $result->exam_id = $request->input('examId'); 
            $result->score = $score;
            $result->save();
        }
    
        $totalQuestions = 50; 
        $percentage = ($score / $totalQuestions) * 100;
    
        return redirect()->route('result', ['examTitle' => $examTitle])->with([
            'examTitle' => $examTitle,
            'percentage' => $percentage,
        ]);
   
    }
    
    public function showResult(Request $request)
    {
        $examTitle = session('examTitle');
        $percentage = session('percentage');
    
        if (!$examTitle || !$percentage) {
            return redirect()->route('student.dashboard'); 
        }
    
        return view('student.result', compact('examTitle', 'percentage'));
    }
    


}
