<?php

namespace App\Models;

use Illuminate\Support\Collection;

class Task
{
    public $id;
    public $name;
    public $status;
    public $created_at;
    public $updated_at;

    // File path for storing tasks
    private static $filePath = 'app/tasks.json';

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->status = $data['status'] ?? 'pending';
        $this->created_at = $data['created_at'] ?? now()->toDateTimeString();
        $this->updated_at = $data['updated_at'] ?? now()->toDateTimeString();
    }

    // Get all tasks
    public static function all()
    {
        $tasks = self::getTasksFromFile();
        return $tasks->map(function ($task) {
            return new self($task);
        });
    }

    // Get tasks from JSON file
    private static function getTasksFromFile()
    {
        $filePath = storage_path(self::$filePath);
        
        if (!file_exists($filePath)) {
            return collect([]);
        }
        
        $tasks = json_decode(file_get_contents($filePath), true) ?? [];
        return collect($tasks);
    }

    // Other methods...
}