<?php

namespace App\Http\Controllers;
use App\Models\Answer;
use App\Models\Payment;
use App\Models\UserResult;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Quiz;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ResultController extends Controller
{

    public function showViewResult($quiz_id)
{
    $student = Auth::user();
    $examId = $student->exam_id;
    $studentName = $student->username;

    $quiz = Quiz::find($quiz_id);
    $examTitle = $quiz ? $quiz->heading : 'Exam';

    $totalQuestions = Question::where('quiz_id', $quiz_id)->count();
    $userAnswers = \DB::table('user_answers')
        ->where('user_id', $student->id)
        ->where('quiz_id', $quiz_id) 
        ->get();

    $totalAttempt = $userAnswers->filter(function ($userAnswer) {
        return !is_null($userAnswer->answer_text) || !is_null($userAnswer->answer_image) || !is_null($userAnswer->answer_sound);
    })->count();

    $totalCorrect = 0;
    $totalIncorrect = 0;
    $totalUnsolved = $totalQuestions - $totalAttempt;

    $correctQuestions = [];
    $incorrectQuestions = [];
    $unsolvedQuestions = [];

    foreach ($userAnswers as $userAnswer) {
        $question = Question::find($userAnswer->question_id);
        $questionText = $question ? $question->question_text : 'Unknown question';

        if (is_null($userAnswer->answer_text) && is_null($userAnswer->answer_image) && is_null($userAnswer->answer_sound)) {
            $unsolvedQuestions[] = [
                'number' => $userAnswer->question_number,
                'text' => $questionText,
                'userAnswer' => 'Unanswered'
            ];
        } else {
            $isCorrect = false;
            $answer = \DB::table('answers')
                ->where('question_id', $userAnswer->question_id)
                ->where('is_correct', true)
                ->first();

            if ($answer) {
                if ($answer->answer_text && $answer->answer_text === $userAnswer->answer_text) {
                    $isCorrect = true;
                } elseif ($answer->answer_image && $answer->answer_image === $userAnswer->answer_image) {
                    $isCorrect = true;
                } elseif ($answer->answer_sound && $answer->answer_sound === $userAnswer->answer_sound) {
                    $isCorrect = true;
                }
            }

            if ($isCorrect) {
                $correctQuestions[] = [
                    'number' => $userAnswer->question_number,
                    'text' => $questionText,
                    'userAnswer' => $userAnswer->answer_text ?? 'No text answer'
                ];
                $totalCorrect++;
            } else {
                $incorrectQuestions[] = [
                    'number' => $userAnswer->question_number,
                    'text' => $questionText,
                    'userAnswer' => $userAnswer->answer_text ?? 'No text answer'
                ];
                $totalIncorrect++;
            }
        }
    }

    $percentage = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0;
    $resultMessage = $percentage >= 50 
        ? 'Congratulations, you passed the exam.' 
        : 'You have failed the exam. Better luck next time.';

    UserResult::create([
        'name' => $studentName,
        'exam_id' => $examId,
        'exam_title' => $examTitle,
        'total_questions' => $totalQuestions,
        'total_attempts' => $totalAttempt,
        'total_correct' => $totalCorrect,
        'percentage' => $percentage,
        'correct_count' => $totalCorrect,
        'incorrect_count' => $totalIncorrect,
        'unsolved_count' => $totalUnsolved,
    ]);

    return view('student.viewresult', [
        'studentName' => $studentName,
        'examId' => $examId,
        'examTitle' => $examTitle,
        'totalQuestions' => $totalQuestions,
        'totalAttempt' => $totalAttempt,
        'totalCorrect' => $totalCorrect,
        'totalIncorrect' => $totalIncorrect,
        'totalUnsolved' => $totalUnsolved,
        'correctQuestions' => $correctQuestions,
        'incorrectQuestions' => $incorrectQuestions,
        'unsolvedQuestions' => $unsolvedQuestions,
        'percentage' => $percentage,
        'resultMessage' => $resultMessage,
    ]);
}

    
    public function showStudentResults()
    {
        $results = UserResult::all(); 

        return view('teacher.studentResult', compact('results'));
    }

    public function deleteStudentResult($id)
{
    $result = UserResult::find($id);

    if ($result) {
        $result->delete();
        return redirect()->route('student.results')->with('success', 'Result deleted successfully.');
    } else {
        return redirect()->route('student.results')->with('error', 'Result not found.');
    }
}  

public function showResults()
{
    $student = Auth::user();
    $studentName = $student->username;

    $resultData = DB::table('user_results')->get();

    DB::table('user_answers')
        ->where('user_id', $student->id)
        ->delete();

    session()->forget('answer');

    return view('student.result', compact('resultData', 'studentName'));
}


public function deleteResult($id)
    {
        $result = UserResult::findOrFail($id);
        $result->delete();

        return redirect()->route('student.result')->with('success', 'Result deleted successfully.');
    }


}