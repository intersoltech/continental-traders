
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">


  <title>@yield('title')</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  {{-- <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet"> --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  @stack('styles')

  <!-- =======================================================
  * Template Name: NiceAdmin
  * Updated: May 30 2023 with Bootstrap v5.3.0
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

  <!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('home') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="Logo">
      <span class="d-none d-lg-block">Continental Traders</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div><!-- End Logo -->

  <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="GET" action="{{ route('search') }}">
      <input type="text" name="query" placeholder="Search" title="Enter search keyword">
      <button type="submit" title="Search"><i class="bi bi-search"></i></button>
    </form>
  </div><!-- End Search Bar -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
          <i class="bi bi-search"></i>
        </a>
      </li><!-- End Search Icon-->

      <!-- Notifications -->
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-bell"></i>
          <span class="badge bg-primary badge-number">4</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            You have 4 new notifications
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
              <h4>Low Inventory</h4>
              <p>Battery stock is below threshold</p>
              <p>30 min ago</p>
            </div>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="notification-item">
            <i class="bi bi-check-circle text-success"></i>
            <div>
              <h4>Order Completed</h4>
              <p>Sale #123 completed successfully</p>
              <p>1 hr ago</p>
            </div>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="dropdown-footer">
            <a href="#">Show all notifications</a>
          </li>
        </ul>
      </li><!-- End Notification Nav -->

      <!-- Messages -->
      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
          <i class="bi bi-chat-left-text"></i>
          <span class="badge bg-success badge-number">3</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
          <li class="dropdown-header">
            You have 3 new messages
            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="message-item">
            <a href="#">
              <img src="{{ asset('assets/img/messages-1.jpg') }}" alt="" class="rounded-circle">
              <div>
                <h4>Store Manager</h4>
                <p>Please restock the 12V batteries</p>
                <p>4 hrs ago</p>
              </div>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li class="dropdown-footer">
            <a href="#">Show all messages</a>
          </li>
        </ul>
      </li><!-- End Messages Nav -->

      <!-- Profile -->
      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ Auth::user()->name }}</h6>
            <span>Administrator</span>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile.show') }}">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('settings') }}">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('help') }}">
              <i class="bi bi-question-circle"></i>
              <span>Need Help?</span>
            </a>
          </li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="bi bi-box-arrow-right"></i>
              <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </li>
        </ul>
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header><!-- End Header -->  

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

  <ul class="sidebar-nav" id="sidebar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav -->

    <!-- Inventory -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('products.*') || Request::routeIs('vendors.*') ? 'active' : '' }}" data-bs-target="#inventory-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-box"></i><span>Inventory</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="inventory-nav" class="nav-content collapse {{ Request::routeIs('products.*') || Request::routeIs('vendors.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('products.index') }}" class="{{ Request::routeIs('products.*') ? 'active' : '' }}">
            <i class="bi bi-circle"></i><span>Products</span>
          </a>
        </li>
        <li>
          <a href="{{ route('vendors.index') }}" class="{{ Request::routeIs('vendors.*') ? 'active' : '' }}">
            <i class="bi bi-circle"></i><span>Vendors</span>
          </a>
        </li>
      </ul>
    </li><!-- End Inventory Nav -->

    <!-- Transactions -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('purchases.*') || Request::routeIs('sales.*') ? 'active' : '' }}" data-bs-target="#transactions-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-cash-coin"></i><span>Transactions</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="transactions-nav" class="nav-content collapse {{ Request::routeIs('purchases.*') || Request::routeIs('sales.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('purchases.index') }}" class="{{ Request::routeIs('purchases.*') ? 'active' : '' }}">
            <i class="bi bi-circle"></i><span>Purchases</span>
          </a>
        </li>
        <li>
          <a href="{{ route('sales.index') }}" class="{{ Request::routeIs('sales.*') ? 'active' : '' }}">
            <i class="bi bi-circle"></i><span>Sales</span>
          </a>
        </li>
      </ul>
    </li><!-- End Transactions Nav -->

    <!-- Customers -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('customers.*') ? 'active' : '' }}" href="{{ route('customers.index') }}">
        <i class="bi bi-person-lines-fill"></i><span>Customers</span>
      </a>
    </li><!-- End Customers Nav -->

    <!-- Reports -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('reports.*') ? 'active' : '' }}" data-bs-target="#reports-nav" data-bs-toggle="collapse" href="#">
        <i class="bi bi-file-earmark-text"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
      </a>
      <ul id="reports-nav" class="nav-content collapse {{ Request::routeIs('reports.*') ? 'show' : '' }}" data-bs-parent="#sidebar-nav">
        <li>
          <a href="{{ route('reports.daily') }}" class="{{ Request::routeIs('reports.daily') ? 'active' : '' }}">
            <i class="bi bi-circle"></i><span>Daily Report</span>
          </a>
        </li>
        <li>
          <a href="{{ route('reports.pdf') }}" class="{{ Request::routeIs('reports.pdf') ? 'active' : '' }}">
            <i class="bi bi-circle"></i><span>Download PDF Report</span>
          </a>
        </li>
        <!-- Daily Sales Report -->
        <li class="nav-item">
          <a class="nav-link {{ request()->is('sales/daily-report*') ? 'active' : '' }}" href="{{ route('sales.dailyReport', ['date' => \Carbon\Carbon::today()->toDateString()]) }}">
            <i class="bi bi-file-text"></i><span>Daily Sales Report</span>
          </a>
        </li><!-- End Daily Sales Report Nav -->
      </ul>
    </li><!-- End Reports Nav -->

    <!-- Profile -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">
        <i class="bi bi-person"></i><span>Profile</span>
      </a>
    </li><!-- End Profile Nav -->

    <!-- Settings -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('settings') ? 'active' : '' }}" href="{{ route('settings') }}">
        <i class="bi bi-gear"></i>
        <span>Settings</span>
      </a>
    </li><!-- End Settings Nav -->

    <!-- Help -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('help') ? 'active' : '' }}" href="{{ route('help') }}">
        <i class="bi bi-question-circle"></i>
        <span>Help</span>
      </a>
    </li><!-- End Help Nav -->

    <!-- Logout -->
    <li class="nav-item">
      <a class="nav-link {{ Request::routeIs('logout') ? 'active' : '' }}" href="{{ route('logout') }}"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Logout</span>
      </a>

      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
      </form>
    </li><!-- End Logout Nav -->

  </ul>

</aside><!-- End Sidebar-->



  @yield('content')

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  {{-- <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  <script src="{{ asset('assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  @stack('scripts')

</body>

</html>