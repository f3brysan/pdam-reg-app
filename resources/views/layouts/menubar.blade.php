 <aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu bg-menu-theme flex-grow-0">
              <div class="container-xxl d-flex h-100">
                <ul class="menu-inner">
                  <!-- Dashboards -->
                 <li class="menu-item {{ request()->is('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-link">
                          <i class="menu-icon tf-icons mdi mdi-home-outline"></i>
                          <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                      </li>                
                </ul>
              </div>
            </aside>