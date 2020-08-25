<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner scroll-scrollx_visible">
        <div class="sidenav-header d-flex align-items-center">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('img/brand/blue.png') }}" class="navbar-brand-img" alt="...">
            </a>
            <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-inner">
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="/home">
                            <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>

                    {{-- Book Link --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('services.index') }}">
                            <i class="fab fa-servicestack text-pink"></i>
                            <span class="nav-link-text">Services</span>
                        </a>
                    </li>

                    {{-- Book Link --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactions.index') }}">
                            <i class="fas fa-money-check-alt text-teal"></i>
                            <span class="nav-link-text">Transactions</span>
                        </a>
                    </li>

                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                <h6 class="navbar-heading p-0 text-muted">Settings</h6>
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                    {{-- Book Link --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sectors.index') }}">
                            <i class="fas fa-tag text-pink"></i>
                            <span class="nav-link-text">Sectors</span>
                        </a>
                    </li>

                    {{-- Book Link --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('paymentmodes.index') }}">
                            <i class="fas fa-tag text-teal"></i>
                            <span class="nav-link-text">Payment Modes</span>
                        </a>
                    </li>

                    {{-- Book Link --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('transactiontypes.index') }}">
                            <i class="fas fa-tag text-pink"></i>
                            <span class="nav-link-text">Transaction Types</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link"  onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt text-danger"></i>
                            <span class="nav-link-text">Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
