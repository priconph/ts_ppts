<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="padding-top: 0px; padding-bottom: 0px;">
  <!-- Brand Logo -->
  <a href="{{ route('dashboard') }}" class="brand-link" style="padding-top: 5px; padding-bottom: 5px; margin-top: 0px; margin-bottom: 0px;">
    <!-- <img src="{{ asset('public/images/pricon_logo2.png') }}"
         alt="CNPTS"
         class="brand-image img-circle elevation-3"
         style="opacity: .8"> -->

      <img src="{{ asset('public/images/pats-logo-cropped.PNG') }}"
         alt="CNPTS"
         class="brand-image"
         style="opacity: 1; margin-top: 5px;">

    <span class="brand-text font-weight-light" style="font-size: 30px;"><b>TS PPTS</b></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-header"><b><h5>FVI MODULES</h5></b></li>
            <li class="nav-item">
              <a href="{{ route('prod_runcard_new') }}" class="nav-link">
                <p> - Visual Inspection</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('defect_escalation') }}" class="nav-link">
                <p> - Defect Escalation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('oqclotapplication_new') }}" class="nav-link">
                <p> - OQC Lot Application</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('overallinspection') }}" class="nav-link">
                <p> - Overall Inspection</p>
              </a>
            </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>