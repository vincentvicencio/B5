<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <img src="{{ asset('images/logo2.png') }}" alt="Magellan Solutions Logo">
        </div>
        <button class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Close sidebar">
    <i class="bi bi-x"></i>
</button>
    </div>

    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"" href="{{ route('dashboard') }}">
                <i class="bi bi-grid-fill"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
             <a href="{{ route('manage.index') }}" 
                                       class="nav-link {{ request()->routeIs('manage.*') ? 'active' : '' }}">
                                        <i class="bi bi-patch-question "></i>
                                        Big Five Personality Test
                                    </a>

        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('respondents.index') ? 'active' : '' }}" href="{{ route('respondents.index') }}">
                <i class="bi bi-people"></i> Respondents
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link " href="#">
                <i class="bi bi-star"></i> Score Matrix
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('interpretation') ? 'active' : '' }}" href="{{route('interpretation')}}">
                <i class="bi bi-lightbulb"></i> Interpretation
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="bi bi-person-workspace"></i> Position Configuration
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{route('admin.index')}}">
                <i class="bi bi-person-fill-gear"></i> Admin
            </a>
        </li>
    </ul>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</div>