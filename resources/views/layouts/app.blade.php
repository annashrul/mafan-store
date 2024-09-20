<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Dashboard</title>
	<!-- Bootstrap CSS -->

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
		rel="stylesheet">
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link
		href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Playpen+Sans:wght@100..800&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
		rel="stylesheet">

		
	<style>
	* {
		font-family: "Playpen Sans", cursive;
		font-weight: 500;
	}

	a {
		font-family: "Playpen Sans", cursive !important;


	}

	h1,
	h2,
	h3,
	h4,
	h5,
	h6 {
		font-family: "Playpen Sans", cursive !important;

	}

	body {
		min-height: 100vh;
		display: flex;
		flex-direction: column;
		font-family:"Playpen Sans", cursive !important;
		background-color: #F4F7FE !important;
	}

	.sidebar {
		padding: 10px;
		height: 100vh;
		position: fixed;
		top: 0;
		left: 0;
		width: 250px;
		background-color: rgb(255, 255, 255) !important;
		display: flex;
		flex-direction: column;
	}

	.main-content {
		margin-left: 270px;
		padding: 5px;

	}

	.wrapper-content {
		padding: 20px;
		border-radius: 5px;
		background-color: rgb(255, 255, 255) !important;

	}

	.sidebar-footer {
		margin-top: auto;
		padding: 10px;
		background-color: rgb(244, 247, 254);
		border-radius: 5px;
		color: #A3AED0 !important
			/* border-top: 1px solid #dee2e6; */
	}

	.avatar-dropdown {
		cursor: pointer;
	}

	.form-control:focus {
		border-color: #4318FF !important;
		/* Contoh warna aktif */
		box-shadow: none !important
	}

	.btn {
		background-color: #4318FF !important;
		/* Contoh warna aktif */
		color: #fff !important;
		border-radius: 5px;
		border: none;
		font-weight: bold !important
	}

	.active a {
		color: #fff;
		background-color: #4318FF;
		/* Contoh warna aktif */
		border-radius: 5px;
	}

	.active>a.nav-link {
		color: #fff !important;
	}

	a.nav-link {
		color: #A3AED0;
		/* font-weight: bold; */
	}

	.active a:hover {
		/* color: inherit !important */
		color: white !important;
	}

	.nav-link:focus,
	.nav-link:hover {
		color: inherit !important;
	}

	.nav-item:hover {
		background-color: rgb(244, 247, 254) !important;
		border-radius: 5px;
		color: #4318FF !important
	}
	.active>.page-link, .page-link.active{
				background-color: #4318FF !important

	}
	a{
		/* color:#4318FF !important */
	}
	
td{
	/* font-weight: 00 !important */
}
td,th {
	white-space: nowrap !important;
	vertical-align: middle !important;
}
.w1{
	width: 1% !important;
}
.text-right{
	text-align: right !important;
}

thead{
	background-color: #EEEEEE !important;
}
tfoot{
	background-color: gray !important;
	color: #EEEEEE !important
}


	</style>
</head>

<body>


	<div class="sidebar">
		<div class="sidebar-header">
			<img src="{{url('/logo.jpg')}}" alt="Image" style="height: 120px;margin-left:30px"/>

			{{-- <h4 class="text-center py-3">Sangkan Jaya</h4> --}}
		</div>
		<ul class="nav flex-column">
			<li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
				<a class="nav-link " href="{{ route('dashboard') }}">
					<i class="fas fa-tachometer-alt"></i> Dashboard
				</a>
			</li>
			@if(Auth::check() && Auth::user()->level === 'admin')

			
			<li class="nav-item {{ Request::is('users') ? 'active' : '' }}">
				<a class="nav-link" href="{{ route('users.index') }}">
					<i class="fas fa-users"></i> Users
				</a>
			</li>
			
			</li>
			<li class="nav-item {{ Request::is('products') ? 'active' : '' }}">
				<a class="nav-link" href="{{ route('products.index') }}">
					<i class="fas fa-box"></i> Products
				</a>
			</li>
			@endif
			<li class="nav-item  {{ Request::is('transactions/create') ? 'active' : '' }}">
				<a class="nav-link" href="{{ route('transactions.create') }}">
					<i class="fas fa-exchange-alt"></i> Transactions
				</a>
			</li>
			<li class="nav-item  {{ Request::is('transactions') ? 'active' : '' }}">
				<a class="nav-link" href="{{ route('transactions.index') }}">
					<i class="fas fa-exchange-alt"></i> Report Transactions
				</a>
			</li>

		</ul>
		<div class="sidebar-footer">
			<!-- Avatar Dropdown -->
			<div class="dropdown avatar-dropdown">
				<a class="d-flex align-items-center text-decoration-none" href="#" role="button" id="userDropdown"
					data-bs-toggle="dropdown" aria-expanded="false">
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
		<div class="wrapper-content">
			@yield('content')

		</div>
	</div>

	<!-- Bootstrap JS and dependencies -->
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
	</script>

</body>

</html>