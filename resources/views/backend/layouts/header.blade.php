<header class="pc-header">
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item pc-sidebar-collapse">
                    <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
                <li class="pc-h-item pc-sidebar-popup">
                    <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto">
            <ul class="list-unstyled">
                <li class="dropdown pc-h-item">
                </li>
                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" data-bs-auto-close="outside" aria-expanded="false">
                        <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="user-avtar">
                        <span>{{ Auth::user()->name }}</span>
                        <span></span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <div class="d-flex mb-1">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image"
                                        class="user-avtar wid-35">
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                    <h6 class="mb-1"></h6>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" style="display: none;">
                                    @csrf
                                </form>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.previousElementSibling.submit();"
                                    class="pc-head-link bg-transparent"><i class="ti ti-power text-danger"></i></a>
                            </div>
                        </div>
                        <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="drp-t1" data-bs-toggle="tab"
                                    data-bs-target="#drp-tab-1" type="button" role="tab" aria-controls="drp-tab-1"
                                    aria-selected="true"><i class="ti ti-user"></i>
                                    Profile</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="mysrpTabContent">
                            <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                aria-labelledby="drp-t1" tabindex="0">
                                <a href="{{ route('profile.edit') }}" class="dropdown-item">
                                    <i class="ti ti-user"></i>
                                    <span>Lihat Profile</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}" style="display: none;">
                                    @csrf
                                </form>
                                <a href="{{ route('logout') }}" class="dropdown-item"
                                    onclick="event.preventDefault(); this.previousElementSibling.submit();">
                                    <i class="ti ti-power"></i>
                                    <span>Keluar</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
