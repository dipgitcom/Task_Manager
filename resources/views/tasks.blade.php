<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 900px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            margin-bottom: 25px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            padding: 15px 20px;
        }
        .card-header h5 {
            margin-bottom: 0;
            font-weight: 600;
            color: #333;
        }
        .card-body {
            padding: 20px;
        }
        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        .btn-primary:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            font-weight: 600;
            color: #555;
            border-bottom-width: 1px;
        }
        .badge {
            font-weight: 500;
            padding: 6px 10px;
            border-radius: 6px;
        }
        .bg-warning {
            background-color: #ffb703 !important;
            color: #333;
        }
        .bg-success {
            background-color: #2a9d8f !important;
        }
        .btn-action {
            padding: 5px 10px;
            border-radius: 6px;
            margin-right: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
        }
        .btn-action i {
            margin-right: 5px;
        }
        .btn-edit {
            background-color: #4895ef;
            border-color: #4895ef;
            color: white;
        }
        .btn-edit:hover {
            background-color: #3d84d6;
            border-color: #3d84d6;
            color: white;
        }
        .btn-success {
            background-color: #2a9d8f;
            border-color: #2a9d8f;
        }
        .btn-success:hover {
            background-color: #238a7e;
            border-color: #238a7e;
        }
        .btn-warning {
            background-color: #ffb703;
            border-color: #ffb703;
            color: #333;
        }
        .btn-warning:hover {
            background-color: #e6a503;
            border-color: #e6a503;
            color: #333;
        }
        .btn-danger {
            background-color: #e63946;
            border-color: #e63946;
        }
        .btn-danger:hover {
            background-color: #d12836;
            border-color: #d12836;
        }
        .page-title {
            color: #333;
            font-weight: 700;
            margin-bottom: 25px;
            border-left: 5px solid #4361ee;
            padding-left: 15px;
        }
        .alert {
            border-radius: 8px;
            font-weight: 500;
        }
        .form-control {
            border-radius: 6px;
            padding: 10px 15px;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            border-color: #4361ee;
        }
        .form-label {
            font-weight: 500;
            color: #555;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        @media (max-width: 767px) {
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }
            .btn-action {
                margin-right: 0;
                margin-bottom: 5px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="page-title">Task Manager</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            </div>
        @endif
        
        <!-- Create Task Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="fas fa-plus-circle me-2"></i>Create New Task</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Task Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter task name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Task
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Task List -->
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-tasks me-2"></i>Tasks</h5>
            </div>
            <div class="card-body">
                @if(count($tasks) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tasks as $task)
                                    <tr>
                                        <td>{{ $task->id }}</td>
                                        <td>{{ $task->name }}</td>
                                        <td>
                                            <span class="badge {{ $task->status == 'completed' ? 'bg-success' : 'bg-warning' }}">
                                                <i class="fas {{ $task->status == 'completed' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                                {{ ucfirst($task->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $task->created_at }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-action btn-edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                
                                                @if($task->status == 'pending')
                                                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="name" value="{{ $task->name }}">
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="btn btn-action btn-success">
                                                            <i class="fas fa-check"></i> Complete
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('tasks.update', $task->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="name" value="{{ $task->name }}">
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="btn btn-action btn-warning">
                                                            <i class="fas fa-undo"></i> Pending
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-action btn-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <p class="lead">No tasks found. Create one!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>