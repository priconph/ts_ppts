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
    <!-- Sidebar user (optional) -->
    <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ asset('public/template/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">Name</a>
      </div>
    </div> -->

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <li class="nav-header"><b><h5>MODULES</h5></b></li>
          <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-microscope"></i>
            <p>
              QC
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('oqcvir_pts') }}" class="nav-link">
                <p>- OQC Inspection</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-box-open"></i>
            <p>
             PACKING
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('packinginspection_pts') }}" class="nav-link">
                <p>- Preliminary Packing</p>
              </a>
            </li>
           </ul>
        </li>

        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-box-open"></i>
            <p>
             FINAL PACKING
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('finalpackinginspection_pts') }}" class="nav-link">
                <p>- Operator/QC Inspection</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('finalpackinginspection_pts_qc_conf') }}" class="nav-link">
                <p>- QC Confirmation (WEB EDI)</p>
              </a>
            </li>
             <li class="nav-item">
              <a href="{{ route('finalpackinginspection_pts_trffic_qc') }}" class="nav-link">
                <p>- Traffic/QC Inspection</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('shipment_summary') }}" class="nav-link">
                <p>
                  - Shipment Summary
                </p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>