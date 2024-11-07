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
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .active {
            background-color: skyblue;
            color: white;
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

        .package-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #f9f9f9;
        }

        .package-card img {
            max-width: 50px;
        }

        .package-info {
            flex-grow: 1;
            margin-left: 20px;
        }

        .package-info h5 {
            font-size: 20px;
            margin-bottom: 5px;
        }

        .status {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 14px;
        }

        .buy-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .buy-button:hover {
            background-color: #218838;
        }

        .back-button-wrapper {
            margin-top: 20px; 
        }

        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            background-color: white; 
            color: #007bff; 
            padding: 10px 15px;
            border-radius: 5px; 
            text-decoration: none;
            border: 1px solid #007bff; 
            font-weight: bold; 
        }

        .back-button:hover {
            background-color: #f0f0f0; 
            color: #0056b3;
            border-color: #0056b3; 
            text-decoration: none;
        }

        .footer {
            margin-top: 20px; /* Ensure footer spacing */
            text-align: center; /* Center align footer text */
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
        <h3>APS KLC TRAIL EXAM</h3>
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

    <!-- Back Button with Spacing -->
    <div class="back-button-wrapper">
        <a href="{{ route('student.dashboard') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

   <!-- Package Card -->
@if($package)
<div class="package-card">
    <img src="{{ asset('/images/package.png') }}" alt="Package Image" width="200px">
    <div class="package-info">
        <h5>{{ $package->name }}</h5>
        <p>{{ $packageQuizzes->count() . ' exam' . ($packageQuizzes->count() !== 1 ? 's' : '') }}</p>
        <span class="status">
            @php
                $isActive = $packageQuizzes->contains(fn($quiz) => $quiz->price === $package->price);
            @endphp
            {{ $isActive ? 'Available' : 'Unavailable' }}
        </span>
    </div>
    @if($isActive) 
        <button class="btn buy-button" 
                data-package-name="{{ $package->name }}" 
                data-price="{{ $package->price }}" 
                data-package-id="{{ $package->id }}" 
                data-toggle="modal" 
                data-target="#buyExamModal">
            BUY RS. {{ $package->price }}
        </button>        
    @endif
</div>
@else
<p>No Package Available</p>
@endif

<!-- Modal for eSewa Payment -->
<div class="modal fade" id="buyExamModal" tabindex="-1" aria-labelledby="buyExamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyExamModalLabel">Buy Exam</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="https://uat.esewa.com.np/epay/main" method="POST">
                    @csrf
                    <h5 id="modal-package-name"></h5>
                    <p id="modal-price"></p>

                    <input type="hidden" name="tAmt" id="totalAmount" value="">
                    <input type="hidden" name="amt" id="packagePriceInput" value="">
                    <input type="hidden" name="txAmt" value="0">
                    <input type="hidden" name="psc" value="0">
                    <input type="hidden" name="pdc" value="0">
                    <input type="hidden" name="scd" value="EPAYTEST">
                    <input type="hidden" name="pid" id="productID" value="">
                    <input type="hidden" name="su" value="{{ route('payment.success') }}">
                    <input type="hidden" name="fu" value="{{ route('payment.failure') }}">

                    <button type="submit" class="btn btn-success">BUY WITH ESEWA</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Attach event listeners to buy buttons
        document.querySelectorAll('.buy-button').forEach(button => {
            button.addEventListener('click', function () {
                const packageName = this.dataset.packageName;
                const price = this.dataset.price;

                // Set modal content
                document.getElementById('modal-package-name').textContent = packageName;
                document.getElementById('modal-price').textContent = `Price: Rs. ${price}`;
                document.getElementById('packagePriceInput').value = price;
                document.getElementById('totalAmount').value = price;
                document.getElementById('productID').value = 'PID_' + Date.now();
            });
        });

        // Handle popup close
        const popup = document.getElementById('payment-popup');
        if (popup) {
            popup.style.display = 'block';
            document.getElementById('close-popup').addEventListener('click', function () {
                popup.style.display = 'none';
            });
        }
    });
</script>

<script>
   $(document).ready(function() {
    $('.buy-button').click(function() {
        const packageName = $(this).data('package-name');
        const packagePrice = $(this).data('price');

        // Set the values in the modal
        $('#modal-package-name').text(packageName);
        $('#modal-price').text('Price: Rs. ' + packagePrice);
        $('#totalAmount').val(packagePrice);
        $('#packagePriceInput').val(packagePrice);
        $('#productID').val('PID_' + Date.now()); // Use a consistent ID format
    });
});
</script>


</div>
<!-- Bootstrap JS, Popper.js, and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>