<div class="sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
                <div class="peer peer-greed">
                    <a class="sidebar-link td-n" href="#">
                        <div class="peers ai-c fxw-nw">
                            <div class="peer">
                                <div class="logo">
                                    <img src="/assets/static/images/logo.png" alt="">
                                </div>
                            </div>
                            <div class="peer peer-greed">
                            <h5 class="lh-1 mB-0 logo-text">{{ __('admin.title.mypet_admin_panel') }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="peer">
                    <div class="mobile-toggle sidebar-toggle">
                        <a href="" class="td-n">
                            <i class="ti-arrow-circle-left"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item">
                <a class='sidebar-link' href="#">
                <span class="icon-holder">
                  <i class="c-brown-500 ti-email"></i>
                </span>
                    <span class="title">{{ __('admin.side_bar.support_tickets') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class='sidebar-link' href="#">
                <span class="icon-holder">
                  <i class="c-blue-500 ti-user"></i>
                </span>
                    <span class="title">{{ __('admin.side_bar.users') }}</span>
                </a>
            </li>
            <li class="nav-item">
            <a class='sidebar-link' href="{{ route('updates.index') }}">
                <span class="icon-holder">
                  <i class="c-deep-orange-500 ti-reload"></i>
                </span>
                    <span class="title">{{ __('admin.side_bar.app_updates') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class='sidebar-link' href="{{ route('materials.index') }}">
                <span class="icon-holder">
                  <i class="c-deep-purple-500 ti-comment-alt"></i>
                </span>
                    <span class="title">{{ __('admin.side_bar.partner_materials') }}</span>
                </a>
            </li>
            <li class="nav-item">
                <a class='sidebar-link' href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <span class="icon-holder">
                  <i class="c-indigo-500 ti-bar-chart"></i>
                </span>
                    <span class="title">{{ __('Logout') }}</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>
                        