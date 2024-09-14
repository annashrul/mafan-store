<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .sidebar-footer {
            margin-top: auto;
            padding: 10px;
            background-color: #e9ecef;
            border-top: 1px solid #dee2e6;
        }
        .avatar-dropdown {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="sidebar">
    <div class="sidebar-header">
        <h4 class="text-center py-3">Admin Dashboard</h4>
    </div>
    <ul class="nav flex-column">
        @if(Auth::check() && Auth::user()->level === 'admin')
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('users.index') }}">
                <i class="fas fa-users"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('transactions.index') }}">
                <i class="fas fa-exchange-alt"></i> Transactions
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('products.index') }}">
                <i class="fas fa-box"></i> Products
            </a>
        </li>
        @elseif(Auth::check() && Auth::user()->level === 'kasir')
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transactions.index') }}">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </a>
            </li>
        @endif

    </ul>
    <div class="sidebar-footer">
        <!-- Avatar Dropdown -->
        <div class="dropdown avatar-dropdown">
            <a class="d-flex align-items-center text-decoration-none" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-user-circle fa-2x"></i>
                <div class="ms-2">
                    <div>{{ Auth::user()->name }}</div>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Edit Profile</a></li>
                <li>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="main-content">
    @yield('content')
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

</body>
</html>
