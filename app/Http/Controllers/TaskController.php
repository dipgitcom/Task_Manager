<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    // File path for storing tasks
    private $filePath;
    
    public function __construct()
    {
        $this->filePath = storage_path('app/tasks.json');
    }

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = $this->getTasksFromFile();
        return view('tasks', ['tasks' => $this->tasksToObjects($tasks)]);
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit($id)
    {
        $tasks = $this->getTasksFromFile();
        $taskIndex = $this->findTaskIndex($tasks, $id);
        
        if ($taskIndex === false) {
            return redirect()->route('tasks.index')
                ->with('error', 'Task not found.');
        }
        
        $task = (object) $tasks[$taskIndex];
        return view('edit-task', compact('task'));
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        $tasks = $this->getTasksFromFile();
        
        $newTask = [
            'id' => count($tasks) > 0 ? max(array_column($tasks, 'id')) + 1 : 1,
            'name' => $request->name,
            'status' => 'pending',
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];
        
        $tasks[] = $newTask;
        $this->saveTasksToFile($tasks);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified task in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required|in:pending,completed',
        ]);

        $tasks = $this->getTasksFromFile();
        $taskIndex = $this->findTaskIndex($tasks, $id);
        
        if ($taskIndex !== false) {
            $tasks[$taskIndex]['name'] = $request->name;
            $tasks[$taskIndex]['status'] = $request->status;
            $tasks[$taskIndex]['updated_at'] = now()->toDateTimeString();
            
            $this->saveTasksToFile($tasks);
            
            return redirect()->route('tasks.index')
                ->with('success', 'Task updated successfully.');
        }
        
        return redirect()->route('tasks.index')
            ->with('error', 'Task not found.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy($id)
    {
        $tasks = $this->getTasksFromFile();
        $taskIndex = $this->findTaskIndex($tasks, $id);
        
        if ($taskIndex !== false) {
            array_splice($tasks, $taskIndex, 1);
            $this->saveTasksToFile($tasks);
            
            return redirect()->route('tasks.index')
                ->with('success', 'Task deleted successfully.');
        }
        
        return redirect()->route('tasks.index')
            ->with('error', 'Task not found.');
    }

    /**
     * Find task index by ID.
     */
    private function findTaskIndex($tasks, $id)
    {
        foreach ($tasks as $index => $task) {
            if ($task['id'] == $id) {
                return $index;
            }
        }
        
        return false;
    }

    /**
     * Convert tasks array to objects.
     */
    private function tasksToObjects($tasks)
    {
        return array_map(function ($task) {
            return (object) $task;
        }, $tasks);
    }

    /**
     * Get tasks from JSON file.
     */
    private function getTasksFromFile()
    {
        if (!file_exists($this->filePath)) {
            return [];
        }
        
        $content = file_get_contents($this->filePath);
        return json_decode($content, true) ?: [];
    }

    /**
     * Save tasks to JSON file.
     */
    private function saveTasksToFile(array $tasks)
    {
        // Create directory if it doesn't exist
        if (!file_exists(dirname($this->filePath))) {
            mkdir(dirname($this->filePath), 0755, true);
        }
        
        file_put_contents($this->filePath, json_encode($tasks, JSON_PRETTY_PRINT));
    }
}