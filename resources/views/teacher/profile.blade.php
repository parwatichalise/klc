@extends('teacher.dashboard')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <div class="card p-4">
            <div class="text-center">
                <h3>TEACHER PROFILE</h3>
            </div>

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
        </div>
    </div>
@endsection