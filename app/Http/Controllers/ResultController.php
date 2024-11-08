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

    // Fetch the exam title
    $quiz = Quiz::find($quiz_id);
    $examTitle = $quiz ? $quiz->heading : 'Exam';

    // Count questions based on quiz_id
    $totalQuestions = Question::where('quiz_id', $quiz_id)->count();
    $userAnswers = \DB::table('user_answers')
        ->where('user_id', $student->id)
        ->where('quiz_id', $quiz_id) 
        ->get();

    // Count total attempts by checking if any of answer_text, answer_image, or answer_sound is filled
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
        if (is_null($userAnswer->answer_text) && is_null($userAnswer->answer_image) && is_null($userAnswer->answer_sound)) {
            $unsolvedQuestions[] = $userAnswer->question_number;
        } else {
            $isCorrect = false;
    
            // Retrieve the correct answer for the question
            $answer = \DB::table('answers')
                ->where('question_id', $userAnswer->question_id)
                ->where('is_correct', true)
                ->first();
    
            if ($answer) {
                // Check each answer field individually
                if ($answer->answer_text && $answer->answer_text === $userAnswer->answer_text) {
                    $isCorrect = true;
                } elseif ($answer->answer_image && $answer->answer_image === $userAnswer->answer_image) {
                    $isCorrect = true;
                } elseif ($answer->answer_sound && $answer->answer_sound === $userAnswer->answer_sound) {
                    $isCorrect = true;
                }
            }
    
            if ($isCorrect) {
                $correctQuestions[] = $userAnswer->question_number;
                $totalCorrect++;
            } else {
                $incorrectQuestions[] = $userAnswer->question_number;
                $totalIncorrect++;
            }
        }
    }
    
    $percentage = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0;
    $resultMessage = $percentage >= 50 
        ? 'Congratulations, you passed the exam.' 
        : 'You have failed the exam. Better luck next time.';

    // Save results to user_results table
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
        // Fetch results from the user_results table
        $results = UserResult::all(); // Adjust as needed to filter or paginate results

        return view('teacher.studentResult', compact('results'));
    }

    public function deleteStudentResult($id)
{
    // Find the result by ID and delete it
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

    // Retrieve the result data for the student
    $resultData = DB::table('user_results')->get();

    // Clear the user_answers table for the logged-in user
    DB::table('user_answers')
        ->where('user_id', $student->id)  // Add condition to only delete the current user's answers
        ->delete();

    // Pass both $resultData and $studentName to the view
    return view('student.result', compact('resultData', 'studentName'));
}



public function deleteResult($id)
    {
        // Find the result by ID and delete it
        $result = UserResult::findOrFail($id);
        $result->delete();

        // Redirect back to the result page with a success message
        return redirect()->route('student.result')->with('success', 'Result deleted successfully.');
    }


}