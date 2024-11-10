<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\user;
use Illuminate\Support\Facades\Auth;


class QuestionController extends Controller
{
    public function create()
    {
        $question = new Question();
        $quizzes = Quiz::all();  
        if (Auth::user() && Auth::user()->role === 'admin') {
            return view('admin.create.question', compact('question', 'quizzes'));
        } else {
            return view('teacher.create.question', compact('question', 'quizzes'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'quiz' => 'required|exists:quizzes,id',
            'question_number' => 'required|integer',
            'sub_question' => 'nullable|string',
            'question_table' => 'nullable|string',
            'question_text' => 'nullable|string|max:255',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_sound' => 'nullable|mimes:mp3,wav,ogg|max:2048',
            'input_type' => 'required|in:text,image,audio',
            'correct_option' => 'required|string|in:option1,option2,option3,option4',
            'option1' => 'required_if:input_type,text|string|max:255|nullable',
            'option2' => 'required_if:input_type,text|string|max:255|nullable',
            'option3' => 'required_if:input_type,text|string|max:255|nullable',
            'option4' => 'required_if:input_type,text|string|max:255|nullable',
            'image_option1' => 'required_if:input_type,image|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_option2' => 'required_if:input_type,image|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_option3' => 'required_if:input_type,image|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_option4' => 'required_if:input_type,image|image|mimes:jpeg,png,jpg,gif|max:2048',
            'audio_option1' => 'required_if:input_type,audio|mimes:mp3,wav,ogg|max:2048',
            'audio_option2' => 'required_if:input_type,audio|mimes:mp3,wav,ogg|max:2048',
            'audio_option3' => 'required_if:input_type,audio|mimes:mp3,wav,ogg|max:2048',
            'audio_option4' => 'required_if:input_type,audio|mimes:mp3,wav,ogg|max:2048',
        ]);

        $question = Question::create([
            'quiz_id' => $request->quiz,
            'question_number' => $request->question_number,
            'question_table' => $request->input('question_table'),
            'question_text' => $request->input('question_text'),
            'sub_question' => $request->input('sub_question'),
            'question_image' => $request->hasFile('question_image') ? $request->file('question_image')->store('question_images', 'public') : null,
            'question_sound' => $request->hasFile('question_sound') ? $request->file('question_sound')->store('question_sounds', 'public') : null,
        ]);

        $this->storeOptions($request, $question->id);

        return redirect()->route('questions.index')->with('success', 'Question created successfully!');
    }

    protected function storeOptions(Request $request, $questionId)
    {
        for ($i = 1; $i <= 4; $i++) {
            $answerType = $request->input('input_type');
            $answerText = $answerType === 'text' ? $request->input('option' . $i) : null;
            $answerImage = $answerType === 'image' && $request->hasFile('image_option' . $i) 
                ? $request->file('image_option' . $i)->store('answer_images', 'public') 
                : null;
            $answerSound = $answerType === 'audio' && $request->hasFile('audio_option' . $i) 
                ? $request->file('audio_option' . $i)->store('answer_sounds', 'public') 
                : null;
    
            Answer::create([
                'question_id' => $questionId,
                'answer_type' => $answerType,
                'answer_text' => $answerText,
                'answer_image' => $answerImage,
                'answer_sound' => $answerSound,
                'is_correct' => $request->correct_option == 'option' . $i,
            ]);
        }
    }
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $quizzes = Quiz::all();

        if ($search) {
            $questions = Question::where('question_text', 'like', '%' . $search . '%')->with('answers')->get();
        } else {
            $questions = Question::with('answers')->get();
        }

        if (Auth::user() && Auth::user()->role === 'admin') {
            return view('admin.list.question-list', compact('questions', 'quizzes')); 
        } else {
            return view('teacher.list.question-list', compact('questions', 'quizzes')); 
        }
    }

    public function fetchQuestions($quizId)
    {
        $questions = Question::with('answers')->where('quiz_id', $quizId)->get();

        if ($questions->isEmpty()) {
            return response()->json([], 200);
        }

        foreach ($questions as $question) {
            foreach ($question->answers as $key => $answer) {
                if ($answer->is_correct) {
                    $question->correct_answer = 'Option ' . ($key + 1);
                    break;
                }
            }
        }

        return response()->json($questions);
    }

    public function edit($id)
    {
        $question = Question::findOrFail($id);    
        $quizzes = Quiz::all();

        if (Auth::user() && Auth::user()->role === 'admin') {
            return view('admin.edit.question-edit', compact('quizzes', 'question'));
        } else {
            return view('teacher.edit.question-edit', compact('quizzes', 'question'));
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quiz' => 'required|exists:quizzes,id',
            'question_number' => 'required|integer',
            'question_table' => 'nullable|string',
            'question_text' => 'nullable|string|max:255',
            'correct_option' => 'required|string|in:option1,option2,option3,option4',
            'input_type' => 'required|in:text,image,audio',
            'option1' => 'nullable|string|max:255',
            'option2' => 'nullable|string|max:255',
            'option3' => 'nullable|string|max:255',
            'option4' => 'nullable|string|max:255',
            'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'question_sound' => 'nullable|mimes:mp3,wav|max:2048',
        ]);

        $question = Question::findOrFail($id);
        $question->quiz_id = $request->quiz;
        $question->question_number = $request->question_number;
        $question->question_text = $request->question_text;
        $question->sub_question = $request->sub_question;
        $question->question_table = $request->input('question_table');
                
        if ($request->hasFile('question_image')) {
            Storage::delete('public/' . $question->question_image);
            $question->question_image = $this->handleFileUpload($request, 'question_image', 'questions');
        }

        if ($request->hasFile('question_sound')) {
            Storage::delete('public/' . $question->question_sound);
            $question->question_sound = $this->handleFileUpload($request, 'question_sound', 'questions');
        }

        $question->save();

        $this->updateOptions($request, $question->id);

        return redirect()->route('questions.index')->with('success', 'Question updated successfully!');
    }

    protected function handleFileUpload(Request $request, $inputName, $folder)
    {
        return $request->file($inputName)->store($folder . '/uploads', 'public');
    }

    protected function updateOptions(Request $request, $questionId)
    {
        Answer::where('question_id', $questionId)->delete(); 

        for ($i = 1; $i <= 4; $i++) {
            $answerType = $request->input('input_type');
            $answerText = $answerType === 'text' ? $request->input('option' . $i) : null;
            $answerImage = $answerType === 'image' ? $request->file('image_option' . $i) : null;
            $answerSound = $answerType === 'audio' ? $request->file('audio_option' . $i) : null;

            if ($answerImage) {
                $answerImage = $answerImage->store('answer_images', 'public');
            }
            if ($answerSound) {
                $answerSound = $answerSound->store('answer_sounds', 'public');
            }

            Answer::create([
                'question_id' => $questionId,
                'answer_type' => $answerType,
                'answer_text' => $answerText,
                'answer_image' => $answerImage,
                'answer_sound' => $answerSound,
                'is_correct' => $request->correct_option == 'option' . $i,
            ]);
        }
    }
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        Storage::delete('public/' . $question->question_image);
        Storage::delete('public/' . $question->question_sound);
        
        Answer::where('question_id', $id)->delete();
        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Question deleted successfully!');
    }

    public function show($id)
    {
        $question = Question::with('answers')->findOrFail($id);
        
        $userAnswer = auth::user()->answers()->where('question_id', $id)->first();

        return view('student.question-detail', compact('question', 'userAnswer'));
    }

    public function submitAnswer(Request $request, $quiz_id, $question_id)
    {
        $request->validate([
            'answer' => 'nullable|exists:answers,id',
        ]);
    
        $question = Question::findOrFail($question_id);
    
        $selectedAnswerText = $request->input('answer')
            ? $question->answers()->where('id', $request->input('answer'))->value('answer_text')
            : null;
    
        $questionImage = $question->question_image; 
        $questionSound = $question->question_sound;
        $answerImage = $request->input('answer') ? $question->answers()->where('id', $request->input('answer'))->value('answer_image') : null;
        $answerSound = $request->input('answer') ? $question->answers()->where('id', $request->input('answer'))->value('answer_sound') : null;
    
        DB::table('user_answers')->updateOrInsert([
            'user_id' => Auth::id(),
            'question_id' => $question->id,
            'quiz_id' => $quiz_id, 
            'question_number' => $question->question_number,
        ], [
            'question_text' => $question->question_text,
            'answer_text' => $selectedAnswerText,
            'question_image' => $questionImage,
            'question_sound' => $questionSound,
            'answer_image' => $answerImage,
            'answer_sound' => $answerSound,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        session(["answer.{$quiz_id}.{$question->id}" => $request->input('answer')]);
    
        $totalQuestions = Question::where('quiz_id', $quiz_id)->count();
        if ($question->question_number < $totalQuestions) {
            return redirect()->route('student.showQuestion', [
                'quiz_id' => $quiz_id,
                'question_number' => $question->question_number + 1,
            ]);
        } else {
            return redirect()->route('student.showQuestion', [
                'quiz_id' => $quiz_id,
                'question_number' => $question->question_number,
            ])->with('message', 'You have completed the quiz. Please submit your answers.');
        }
    }
    
}

    
    

