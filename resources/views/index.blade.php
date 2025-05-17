<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panda Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background: #f8fafc;
        }
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 8px 24px rgba(0,0,0,0.13);
        }
        .card.border-success {
            border: 2px solid #198754 !important;
        }
        .modal-content {
            border-radius: 1rem;
        }
        .btn-outline-primary, .btn-outline-danger, .btn-outline-success {
            border-radius: 50%;
            width: 2.2rem;
            height: 2.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .btn-group .btn {
            margin-right: 0.3rem;
        }
        .btn-group .btn:last-child {
            margin-right: 0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('tasks.index') }}">
                <i class="fa-solid fa-list-check fa-lg"></i>
                <span>Panda Task</span>
            </a>
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createTaskModal">
                <i class="fa-solid fa-plus"></i> New Task
            </button>
        </div>
    </nav>

    <div class="container py-5">

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            @foreach ($tasks as $task)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 {{ $task->completed ? 'border-success' : '' }}">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2 {{ $task->completed ? 'text-decoration-line-through text-muted' : ''}}">
                                {{ $task->title }}
                            </h5>
                            <p class="card-text flex-grow-1 text-secondary">
                                {{ $task->description }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editTaskModal{{ $task->id }}" title="Edit">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('tasks.destroy', $task) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure?')" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <form action="{{ route('tasks.toggle-complete', $task) }}" method="post" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="btn btn-sm {{ $task->completed ? 'btn-success' : 'btn-outline-success' }}"
                                        title="{{ $task->completed ? 'Mark as Incomplete' : 'Mark as Complete' }}">
                                        <i class="fa-solid fa-{{ $task->completed ? 'check' : 'circle' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Task Modal -->
                <div class="modal fade" id="editTaskModal{{ $task->id }}" tabindex="-1" aria-labelledby="editTaskModalLabel{{ $task->id }}"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="editTaskModalLabel{{ $task->id }}">Edit Task</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('tasks.update', $task) }}" method="post">
                                <div class="modal-body">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-3">
                                        <label for="title{{ $task->id }}" class="form-label">Title</label>
                                        <input type="text" class="form-control" name="title" id="title{{ $task->id }}"
                                            value="{{ $task->title }}" required />
                                    </div>
                                    <div class="mb-3">
                                        <label for="description{{ $task->id }}" class="form-label">Description</label>
                                        <textarea class="form-control" name="description" id="description{{ $task->id }}" rows="3">{{ $task->description }}</textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Create Task Modal -->
    <div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createTaskModalLabel">Create New Task</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.store') }}" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="createTitle" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title" id="createTitle" required />
                        </div>
                        <div class="mb-3">
                            <label for="createDescription" class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="createDescription" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>

</html>
