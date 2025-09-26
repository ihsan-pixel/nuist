<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{ asset('build/images/logo favicon 1.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('build/images/logo 1.png') }}" alt="" height="40">
                    </span>
                </a>

                <a href="index" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('build/images/logo favicon 2 light.png') }}" alt="" height="30">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('build/images/logo favicon 2 light.png') }}" alt="" height="50">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

           <!-- App Search-->
           {{-- <form class="app-search d-none d-lg-block">
            <div class="position-relative">
                <input type="text" class="form-control" placeholder="@lang('translation.Search')">
                <span class="bx bx-search-alt"></span>
            </div>
        </form> --}}

        <div class="dropdown dropdown-mega d-none d-lg-block ms-2">
            {{-- <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">
                <span key="t-megamenu">@lang('translation.Mega_Menu')</span>
                <i class="mdi mdi-chevron-down"></i>
            </button>
            <div class="dropdown-menu dropdown-megamenu">
                <div class="row">
                    <div class="col-sm-8">

                        <div class="row">
                            <div class="col-md-4">
                                <h5 class="font-size-14 mt-0" key="t-ui-components">@lang('translation.UI_Components')</h5>
                                <ul class="list-unstyled megamenu-list">
                                    <li>
                                        <a href="javascript:void(0);" key="t-lightbox">@lang('translation.Lightbox')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-range-slider">@lang('translation.Range_Slider')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-sweet-alert">@lang('translation.Sweet_Alert')</span>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-rating">@lang('translation.Rating')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-forms">@lang('translation.Forms')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-tables">@lang('translation.Tables')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-charts">@lang('translation.Charts')</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <h5 class="font-size-14 mt-0" key="t-applications">@lang('translation.Applications')</h5>
                                <ul class="list-unstyled megamenu-list">
                                    <li>
                                        <a href="javascript:void(0);" key="t-ecommerce">@lang('translation.Ecommerce')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-calendar">@lang('translation.Calendars')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-email">@lang('translation.Email')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-projects">@lang('translation.Projects')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-tasks">@lang('translation.Tasks')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-contacts">@lang('translation.Contacts')</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-md-4">
                                <h5 class="font-size-14 mt-0" key="t-extra-pages">@lang('translation.Extra_Pages')</h5>
                                <ul class="list-unstyled megamenu-list">
                                    <li>
                                        <a href="javascript:void(0);" key="t-light-sidebar">@lang('translation.Light_Sidebar')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-compact-sidebar">@lang('translation.Compact_Sidebar')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-horizontal">@lang('translation.Horizontal_layout')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-maintenance">@lang('translation.Maintenance')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-coming-soon">@lang('translation.Coming_Soon')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-timeline">@lang('translation.Timeline')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-faqs">@lang('translation.FAQs')</a>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            <div class="col-sm-6">
                                <h5 class="font-size-14 mt-0" key="t-ui-components">@lang('translation.UI_Components')</h5>
                                <ul class="list-unstyled megamenu-list">
                                    <li>
                                        <a href="javascript:void(0);" key="t-lightbox">@lang('translation.Lightbox')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-range-slider">@lang('translation.Range_Slider')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-sweet-alert">@lang('translation.Sweet_Alert')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-rating">@lang('translation.Rating')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-forms">@lang('translation.Forms')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-tables">@lang('translation.Tables')</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" key="t-charts">@lang('translation.Charts')</a>
                                    </li>
                                </ul>
                            </div>

                            <div class="col-sm-5">
                                <div>
                                    <img src="{{ asset ('build/images/megamenu-img.png') }}" alt="" class="img-fluid mx-auto d-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> --}}
        </div>
    </div>

    <div class="d-flex">

<div class="dropdown d-inline-block">
            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img class="rounded-circle header-profile-user" src="{{ isset(Auth::user()->avatar) ? asset('storage/' . Auth::user()->avatar) : asset('build/images/users/avatar-1.jpg') }}"
                    alt="Header Avatar">
                <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{ucfirst(Auth::user()->name)}}</span>
                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                {{-- <a class="dropdown-item" href="contacts-profile"><i class="bx bx-user font-size-16 align-middle me-1"></i> <span key="t-profile">@lang('translation.Profile')</span></a>
                <a class="dropdown-item" href="#"><i class="bx bx-wallet font-size-16 align-middle me-1"></i> <span key="t-my-wallet">@lang('translation.My_Wallet')</span></a>
                <a class="dropdown-item d-block" href="#" data-bs-toggle="modal" data-bs-target=".change-password"><span class="badge bg-success float-end">11</span><i class="bx bx-wrench font-size-16 align-middle me-1"></i> <span key="t-settings">@lang('translation.Settings')</span></a>
                <a class="dropdown-item" href="#"><i class="bx bx-lock-open font-size-16 align-middle me-1"></i> <span key="t-lock-screen">@lang('translation.Lock_screen')</span></a> --}}
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="javascript:void();" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="bx bx-power-off font-size-16 align-middle me-1 text-danger"></i> <span key="t-logout">@lang('translation.Logout')</span></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
</header>
<!--  Change-Password example -->
<div class="modal fade change-password" tabindex="-1" role="dialog"
aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="change-password">
                    @csrf
                    <input type="hidden" value="{{ Auth::user()->id }}" id="data_id">
                    <div class="mb-3">
                        <label for="current_password">Current Password <span class="text-danger">*</span></label>
                        <input id="current-password" type="password"
                            class="form-control @error('current_password') is-invalid @enderror"
                            name="current_password" autocomplete="current_password"
                            placeholder="Enter Current Password" value="{{ old('current_password') }}">
                        <div class="text-danger" id="current_passwordError" data-ajax-feedback="current_password"></div>
                    </div>

                    <div class="mb-3">
                        <label for="newpassword">New Password <span class="text-danger">*</span></label>
                        <input id="password" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            autocomplete="new_password" placeholder="Enter New Password">
                        <div class="text-danger" id="passwordError" data-ajax-feedback="password"></div>
                    </div>

                    <div class="mb-3">
                        <label for="userpassword">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            autocomplete="new_password" placeholder="Enter New Confirm password">
                        <div class="text-danger" id="password_confirmError" data-ajax-feedback="password-confirm"></div>
                    </div>

                    <div class="mt-3 d-grid">
                        <button class="btn btn-primary waves-effect waves-light UpdatePassword" data-id="{{ Auth::user()->id }}"
                            type="submit">Update Password</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

