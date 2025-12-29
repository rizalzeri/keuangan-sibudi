<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a class="logo d-flex align-items-center">
            <img src="/assets/img/logo.png" alt="">
            <span class="d-none d-lg-block">BUMDES PRO</span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->




    <nav class="header-nav ms-auto">


        <ul class="d-flex align-items-center">

            @auth

                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        @if (auth()->user()->desa)
                            <span class="dropdown-toggle ps-2">{{ auth()->user()->desa }}</span>
                        @else
                            <span class="dropdown-toggle ps-2">{{ auth()->user()->name }}</span>
                        @endif
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">

                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Sign Out</span></button>
                            </form>

                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->
            @endauth





        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
