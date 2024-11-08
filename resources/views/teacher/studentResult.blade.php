@extends('teacher.dashboard')

@section('title', 'Student Results')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Detail Of Student Result</h2>

    <style>
        /* Custom table border color */
        .table-bordered, .table-bordered th, .table-bordered td {
            border: 1px solid black !important;
        }
        .table thead th {
            border-bottom: 2px solid black !important;
        }
    </style>

    <!-- Display success or error messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Responsive table wrapper -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>S.N</th>
                    <th>Name</th>
                    <th>Exam Id</th>
                    <th>Title</th>
                    <th>Total Questions</th>
                    <th>Total Attempts</th>
                    <th>Total Correct</th>
                    <th>Your %</th>
                    <th>Correct</th>
                    <th>Incorrect</th>
                    <th>Unsolved</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                <tr>
                    <td class="py-2 px-3">{{ $result->id }}</td>
                    <td class="py-2 px-3">{{ $result->name }}</td>
                    <td class="py-2 px-3">{{ $result->exam_id }}</td>
                    <td class="py-2 px-3">{{ $result->exam_title }}</td>
                    <td class="py-2 px-3">{{ $result->total_questions }}</td>
                    <td class="py-2 px-3">{{ $result->total_attempts }}</td>
                    <td class="py-2 px-3">{{ $result->total_correct }}</td>
                    <td class="py-2 px-3">{{ $result->percentage }}%</td>
                    <td class="py-2 px-3">{{ $result->correct_count }}</td>
                    <td class="py-2 px-3">{{ $result->incorrect_count }}</td>
                    <td class="py-2 px-3">{{ $result->unsolved_count }}</td>
                    <td class="py-2 px-3">
                        <span class="{{ $result->percentage > 50 ? 'text-success' : 'text-danger' }}">
                            {{ $result->percentage > 50 ? 'Pass' : 'Fail' }}
                        </span>
                    </td>
                    <td class="py-2 px-3">
                        <form action="{{ route('student.result.delete', $result->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this result?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
