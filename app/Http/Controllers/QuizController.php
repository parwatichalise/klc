<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Tag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::with('tags')->paginate(10);
        // Directly check the user's role
        $view = Auth::user() && Auth::user()->role === 'admin' ? 'admin.list.quiz-list' : 'teacher.list.quiz-list';
        return view($view, compact('quizzes'));
    }

    public function create()
    {
        $tags = Tag::all();
        // Directly check the user's role
        $view = Auth::user() && Auth::user()->role === 'admin' ? 'admin.create.quiz' : 'teacher.create.quiz';
        return view($view, compact('tags'));        
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'heading' => 'required|string|max:255',
            'sub_heading' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'time_duration' => 'required|string', 
            'active' => 'required|boolean',
            'tags' => 'required|array|exists:tags,id', // Ensure tags exist
        ]);

        $quiz = new Quiz();
        
        if ($request->hasFile('photo')) {
            $quiz->photo = $request->file('photo')->store('quizzes', 'public');
        }
        
        $quiz->fill($request->only('heading', 'sub_heading', 'price', 'time_duration', 'active'));
        $quiz->created_by = Auth::id(); // Use Auth::id()
        $quiz->save();
        
        $quiz->tags()->attach($request->input('tags'));

        return redirect()->route('quizzes.index')->with('success', 'Quiz added successfully.');
    }

    public function edit(Quiz $quiz)
    {
        $tags = Tag::all();         
        // Directly check the user's role
        $view = Auth::user() && Auth::user()->role === 'admin' ? 'admin.edit.quiz-edit' : 'teacher.edit.quiz-edit';
        return view($view, compact('quiz', 'tags'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'heading' => 'required|string|max:255',
            'sub_heading' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'time_duration' => 'required|string',
            'tags' => 'array|exists:tags,id', 
            'active' => 'required|boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($quiz->photo) {
                Storage::delete($quiz->photo); 
            }
            $quiz->photo = $request->file('photo')->store('quizzes', 'public');
        }
    
        $quiz->fill($request->only('heading', 'sub_heading', 'price', 'time_duration', 'active'));
        $quiz->save();
    
        if ($request->has('tags')) {
            $quiz->tags()->sync($request->input('tags'));
        }
    
        return redirect()->route('quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->photo) {
            Storage::delete($quiz->photo); 
        }
        $quiz->delete();
        return redirect()->route('quizzes.index')->with('success', 'Quiz deleted successfully.');
    }

    public function saveTime(Request $request, $quizId)
{
    session(['remaining_time' => $request->input('remaining_time')]);
    return response()->json(['status' => 'success']);
}

}