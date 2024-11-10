<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard</title>

<link rel="stylesheet" href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback') }}">
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">
<link href="{{ asset('css/app.css') }}" rel="stylesheet">


</head>
<body class="hold-transition sidebar-mini layout-fixed">
  
<div class="wrapper">

  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="dist/img/AdminLTELogo.png" alt="AdminLTELogo" height="60" width="60">
  </div>

  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('login') }}" class="nav-link">Login</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>
  
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
        <a href="#" class="nav-link" id="logout-button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="Admin Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Admin</a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item menu-open">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>Profile</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-edit"></i>
              <p>Create <i class="right fas fa-angle-left"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('quizzes.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Quiz</p>
                </a>
            </li>  
            <li class="nav-item">
              <a href="{{ route('questions.create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Question Answer</p>
              </a>
          </li>  
              <li class="nav-item">
                <a href="{{ route('tags.create') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tag</p>
                </a>
            </li>   
            <li class="nav-item">
              <a href="{{ route('packages.create') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Package</p>
              </a>
          </li>     
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-list"></i>
              <p>List Update <i class="fas fa-angle-left right"></i></p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ route('quizzes.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Quiz</p>
                </a>
            </li>  
            <li class="nav-item">
              <a href="{{ route('questions.index') }}" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Question</p>
              </a>
          </li>  
         
              <li class="nav-item">
                <a href="{{ route('tags.index') }}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Tag</p>
                </a>
            </li>

            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('user.list') }}" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>Users <i class="fas fa-angle-left right"></i></p>
            </a>
            
          </li>

<li class="nav-item">
  <a href="{{ route('admin.student.results') }}" class="nav-link">
      <i class="nav-icon fas fa-poll"></i>
      <p>Results</p>
  </a>
</li>

           <li class="nav-item">
           <a href="{{ route('payment.index') }}" class="nav-link">
           <i class="nav-icon fas fa-credit-card"></i>
           <p>Payments</p>
            </a>
        </li>

            <ul class="nav nav-treeview">
            </ul>
          </li>

         

        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1> 
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">
        @yield('content') 
      </div>
    </section>
  </div>

  <aside class="control-sidebar control-sidebar-dark">
  </aside>
</div>

<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('dist/js/adminlte.js') }}"></script>
@yield('scripts')
</body>
</html>