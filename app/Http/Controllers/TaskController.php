<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class TaskController extends Controller
{
    //get all tasks
    // ne folosim de elokent
    public function index()
    {
        $tasks = auth()->user()->tasks()->with('category')->latest()->get();
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function show(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        $task->load('category');
        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|min:3',
            'description' => 'nullable',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id'=> 'nullable|exists:categories,id',
            'completed'=> 'boolean',
        ]);

        $data = [
            'title'=> $request->title,
            'description'=> $request->description,
            'user_id' => auth()->id(),
            'category_id'=> $request->category_id,
            'completed' => $request->boolean('completed'),
        ];

        if($request->hasFile('photo')) {
            $path = $request->file('photo')->store('task-photos','public');
            $data['photo'] = $path;
        }

        Task::create( $data );
        return redirect()->route('tasks.index')->with('success','Task created successfully');
    }

    public function toggleComplete(Task $task){
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        $task->update(['completed' => !$task->completed]);
        return redirect()->back()->with('success','Task status updated');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) {
            abort(403);
        }
        if ($task->photo) {
            Storage::disk('public')->delete($task->photo);
        }
        $task->delete();
        return redirect()->route('tasks.index')->with('success','Task deleted successfully');
    }

}


