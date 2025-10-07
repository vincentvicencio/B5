<nav class="navbar navbar-expand-lg main-navbar">
    <div class="container-fluid">
        <!-- Hamburger -->
        <button class="btn p-0 me-3 navbar-toggler-sidebar" type="button" id="menu-toggle" aria-label="Toggle sidebar">
            <i class="bi bi-list" id="menu-icon"></i>
        </button>

        <div class="d-flex align-items-center ms-auto">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link profile-toggler" href="javascript:void(0);" id="userDropdown">
                        <i class="bi bi-person-circle"></i>
                    </a>
                    
                </li>
            </ul>
        </div>
        <ul class="dropdown-menu" id="userDropdownMenu">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                  @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    </div>
</nav>
