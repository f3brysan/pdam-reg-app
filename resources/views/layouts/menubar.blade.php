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
      @role('pimpinan|admin')
      <li class="menu-item {{ request()->is('laporan-pemasangan*') ? 'active' : '' }}">
        <a href="{{ route('laporan-pemasangan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-file-outline"></i>
          <div data-i18n="Laporan Pemasangan">Laporan Pemasangan</div>
        </a>
      </li>
      @endrole
      @role('admin')
      <li class="menu-item {{ request()->is('permohonan*') ? 'active' : '' }}">
        <a href="{{ route('permohonan.index') }}" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-file-outline"></i>
          <div data-i18n="Permohonan">Permohonan</div>
        </a>
      </li>
      <li class="menu-item menu-dropdown {{ request()->is('master*') ? 'active' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons mdi mdi-database-outline"></i>
          <div data-i18n="Master">Master</div>
        </a>
        <ul class="menu-sub">
          <li class="menu-item {{ request()->is('master/pekerjaan*') ? 'active' : '' }}">
            <a href="{{ route('ms_pekerjaans.index') }}" class="menu-link">
              <i class="menu-icon tf-icons mdi mdi-briefcase-outline"></i>
              <div data-i18n="Master Pekerjaan">Master Pekerjaan</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('master/jenis-meteran*') ? 'active' : '' }}">
            <a href="{{ route('ms_jenis_meterans.index') }}" class="menu-link">
              <i class="menu-icon tf-icons mdi mdi-gauge"></i>
              <div data-i18n="Master Jenis Meteran">Master Jenis Meteran</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('master/jenis-tempat-tinggal*') ? 'active' : '' }}">
            <a href="{{ route('ms_jenis_tempat_tinggals.index') }}" class="menu-link">
              <i class="menu-icon tf-icons mdi mdi-home-city-outline"></i>
              <div data-i18n="Master Jenis Tempat Tinggal">Master Jenis Tempat Tinggal</div>
            </a>
          </li>
          <li class="menu-item {{ request()->is('master/jenis-dokumen*') ? 'active' : '' }}">
            <a href="{{ route('ms_jenis_dokumens.index') }}" class="menu-link">
              <i class="menu-icon tf-icons mdi mdi-file-outline"></i>
              <div data-i18n="Master Jenis Dokumen">Master Jenis Dokumen</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item {{ request()->is('user*') ? 'active' : '' }}">
        <a href="{{ route('users.index') }}" class="menu-link">
          <i class="menu-icon tf-icons mdi mdi-account-outline"></i>
          <div data-i18n="User">User</div>
        </a>
      </li>
      @endrole
    </ul>
  </div>
</aside>