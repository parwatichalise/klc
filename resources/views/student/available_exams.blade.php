<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPS-TOPIK UBT Trail Exam</title>
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        .sidebar {
            height: 100vh;
            width: 200px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #f8f9fa;
            padding-top: 20px;
        }

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: #333;
            display: block;
        }

        .sidebar a:hover {
            background-color: #007bff;
            color: white;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        .top-bar {
            background-color: #007bff;
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .active {
            background-color: skyblue;
            color: white;
        }

        .profile-section {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
            max-width: 500px;
            position: relative;
        }

        .profile-section h3 {
            color: #007bff;
            text-align: center;
        }

        .profile-section .form-group {
            margin-bottom: 15px;
        }

        .hover-menu {
            display: none;
            position: absolute;
            background-color: white;
            border: 1px solid #ddd;
            z-index: 1000;
            right: 0;
            width: 150px;
        }

        .user-icon:hover .hover-menu {
            display: block;
        }

        .hover-menu a {
            color: black;
            padding: 10px;
            text-decoration: none;
            display: block;
        }

        .hover-menu a:hover {
            background-color: #007bff;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .user-info .user-name {
            display: flex;
            align-items: center;
        }

        .user-info .user-name span {
            margin-right: 8px;
        }

        .user-info .user-name .fas {
            font-size: 30px;
        }

        .user-info span.role {
            margin-left: 0px;
        }

        .result-card {
            padding: 15px;
        }

        /* Custom changes */
        .card-header h4 {
            color: #448EE4;
        }

        .card-body {
            padding: 10px;
        }

        .table th,
        .table td {
            text-align: center;
            vertical-align: middle;
        }

        .d-flex {
            justify-content: space-between;
        }

        /* Date input styles */
        .date-filter {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            margin-left: 50%;
        }

        .date-filter label {
            margin-right: 5px;
        }

        .date-filter input[type="date"] {
            border-radius: 4px;
            padding: 3px;
            margin: 0 2px;
        }

        .page-control {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .badge-available {
            border: 1px solid;
            padding: 3px 5px;
            font-size: 12px;
            border-radius: 12px;
            display: inline-block;
            margin-right: 3px;
        }

        .badge-available {
            color: #008631;
            border-color: #008631;
        }

    </style>
</head>

<body>

<div class="sidebar">
    <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="fas fa-list"></i> Exam List
    </a>
    <a href="#" class="{{ request()->routeIs('live.exam') ? 'active' : '' }}">
        <i class="fas fa-play-circle"></i> Live Exam
    </a>
    <a href="{{ route('student.result') }}" class="{{ request()->routeIs('result') ? 'active' : '' }}">
        <i class="fas fa-poll"></i> Results
    </a>
    <a href="{{ route('profile') }}" class="{{ request()->routeIs('profile') ? 'active' : '' }}">
        <i class="fas fa-user"></i> Profile
    </a>
</div>

<div class="content">
    <!-- Top Bar -->
    <div class="top-bar">
        <h3>EPS-TOPIK UBT TRAIL EXAM</h3>
        <div class="user-icon">
            <div class="user-info">
                <div class="user-name">
                    <span>{{ $studentName }}</span>
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="role">Student</span> 
            </div>
            <div class="hover-menu">
                <a href="{{ route('profile') }}">Profile</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="nav-link" id="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
    </div>


    <h2 class="mt-4"><strong>Available Exams</strong></h2>
    <div class="exam-list d-flex flex-wrap">
        @if ($quizzes->isEmpty())
            <p>No available exams with packages at this time. Please check back later.</p>
        @else
            @foreach ($quizzes as $quiz)
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">{{ $quiz->heading }}</h5>
                        @if($quiz->photo)
                            <img src="/storage/{{ $quiz->photo }}" alt="{{ $quiz->heading }}" width="50" height="50" class="me-2">
                        @else
                            <img src="/images/exam_logo.png" alt="Exam Logo" width="50" height="50" class="me-2">
                        @endif
                        <h6><strong>{{ $quiz->sub_heading }}</strong></h6>  
                        <div>
                            Price: {{ $quiz->price ? '$' . $quiz->price : 'Free' }}<br>
                            Duration: {{ $quiz->time_duration }} minutes
                        </div>                      
                        @if($quiz->tags)
                            @foreach ($quiz->tags as $tag)
                                <span class="rounded-badge">{{ $tag->name }}</span>
                            @endforeach
                        @endif
                        <br><br>
                        <span class="badge-available">{{ $quiz->active ? 'Available' : 'Unavailable' }}</span>
                        @if($quiz->active)
                            <a href="{{ route('exam', ['examTitle' => $quiz->heading]) }}" class="btn btn-primary mt-2">START EXAM</a>
                        @endif                    
                    </div>
                </div>
            @endforeach
        @endif
    </div>    
    
    {{-- Pagination Links --}}
    <div class="pagination" style="margin-top: 20px;">
        {{ $quizzes->links() }}
        <p>Showing {{ $quizzes->firstItem() }} to {{ $quizzes->lastItem() }} of {{ $quizzes->total() }} exams</p>
    </div>
</div>    

</body>
</html>