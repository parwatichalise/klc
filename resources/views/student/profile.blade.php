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
            background-color: #007bff; /* Your desired top bar color */
            padding: 10px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .active {
            background-color: skyblue;
            color: white;
        }

        .profile-section {
            margin: 20px auto; /* Center align with auto margins */
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f8f9fa;
            max-width: 500px; /* Set max width for centering */
            position: relative; /* For absolute positioning of edit button */
        }

        .profile-section h3 {
            color: #007bff;
            text-align: center; /* Center title */
        }

        .profile-section .form-group {
            margin-bottom: 15px;
        }

        /* Styles for the hover menu */
        .hover-menu {
            display: none; /* Hide by default */
            position: absolute; /* Position relative to the dropdown */
            background-color: white; /* Background color for the dropdown */
            border: 1px solid #ddd; /* Border for the dropdown */
            z-index: 1000; /* Ensure it appears above other content */
            right: 0; /* Align it to the right */
            width: 150px; /* Width of the dropdown */
        }

        .user-icon:hover .hover-menu {
            display: block; /* Show on hover */
        }

        .hover-menu a {
            color: black; /* Color for dropdown items */
            padding: 10px; /* Padding for items */
            text-decoration: none; /* No underline */
            display: block; /* Block level */
        }

        .hover-menu a:hover {
            background-color: #007bff; /* Change color on hover */
            color: white; /* Text color on hover */
        }

        .edit-button {
            position: absolute; /* Positioning for lower right corner */
            right: 20px;
            bottom: 20px;
        }

        /* Align user icon and text */
        .user-info {
            display: flex;
            flex-direction: column; /* Stack elements vertically */
            align-items: flex-start; /* Align items to the left */
        }

        .user-info .user-name {
            display: flex; /* Use flexbox for name and icon */
            align-items: center; /* Center the icon with text */
        }

        .user-info .user-name span {
            margin-right: 8px; /* Space between text and icon */
        }

        .user-info .user-name .fas {
            font-size: 30px; /* Size of the Font Awesome icon */
        }

        /* To ensure alignment of the name and role */
        .user-info span.role {
            margin-left: 0px; /* Adjust margin to align with the name */
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
    <div class="top-bar ">
        <h3 >APS KLCTRAIL EXAM</h3>
        <div class="user-icon">
            <div class="user-info">
            <div class="user-name">
                <span>{{ auth()->user()->username }}</span> <!-- Updated username will display here -->
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

    <!-- Profile Section -->
     <!-- Profile Edit Form -->
     <form action="{{ route('admin.updateProfile') }}" method="POST">
                @csrf
                @method('PUT') <!-- Use PUT for updating existing data -->

                <div class="row mt-4">
                    <!-- First Name and Last Name -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="firstName">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstname" value="{{ auth()->user()->firstname }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastname" value="{{ auth()->user()->lastname }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Email and Username -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="{{ auth()->user()->username }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Contact and Role -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact">Contact</label>
                            <input type="text" class="form-control" id="contact" name="contact" value="{{ auth()->user()->contact }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="role">Role</label>
                            <input type="text" class="form-control" id="role" name="role" value="{{ auth()->user()->role }}" disabled>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Save Changes</button>
                </div>
            </form>

<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
