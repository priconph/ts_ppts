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

        <li class="nav-header">MODULES</li>
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-microscope"></i>
            <p>
              FINAL VISUAL
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('prod_runcard_new') }}" class="nav-link">
                <i class="far fa-microscope nav-icon"></i>
                <p>Visual Inspection</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('defect_escalation') }}" class="nav-link">
                <i class="far fa-microscope nav-icon"></i>
                <p>Defect Escalation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('oqclotapplication_new') }}" class="nav-link">
                <i class="far fa-microscope nav-icon"></i>
                <p>OQC Lot Application</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('overallinspection') }}" class="nav-link">
                <i class="far fa-microscope nav-icon"></i>
                <p>Overall Inspection</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a href="{{ route('oqcvir_pts') }}" class="nav-link">
            <i class="fas fa-microscope"></i>
            <p>
              OQC Inspection
            </p>
          </a>
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
              <a href="{{ route('packing_pts') }}" class="nav-link">
                <i class="far fa-box-open nav-icon"></i>
                <p>Packing Confirmation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('packinginspection_pts') }}" class="nav-link">
                <i class="far fa-box-open nav-icon"></i>
                <p>Preliminary Packing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('supervisorvalidation_pts') }}" class="nav-link">
                <i class="far fa-box-open nav-icon"></i>
                <p>Supervisor Validation</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('finalpackinginspection_pts') }}" class="nav-link">
                <i class="far fa-box-open nav-icon"></i>
                <p>Final QC Packing Inspt</p>
              </a>
            </li>
          </ul>
        </li>









        
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
<!--         <li class="nav-item has-treeview">
          <a href="{{ route('dashboard') }}" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
 -->        <!-- <li class="nav-header">ADMINISTRATOR</li>
        <li class="nav-item">
          <a href="{{ route('user') }}" class="nav-link">
            <i class="fas fa-users"></i>
            <p>
              User
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('station') }}" class="nav-link">
            <i class="fas fa-object-group"></i>
            <p>
              Station
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('machine') }}" class="nav-link">
            <i class="fas fa-hdd"></i>
            <p>
              Machine
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('material') }}" class="nav-link">
            <i class="fas fa-puzzle-piece"></i>
            <p>
              Material
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('mode_of_defect') }}" class="nav-link">
            <i class="fas fa-trash"></i>
            <p>
              Mode of Defect
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('assembly_line') }}" class="nav-link">
            <i class="fas fa-list"></i>
            <p>
              Assembly Lines
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-item">
          <a href="{{ route('device') }}" class="nav-link">
            <i class="fas fa-hdd"></i>
            <p>
              Device
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-item">
          <a href="{{ route('materialprocess') }}" class="nav-link">
            <i class="fas fa-list-ol"></i>
            <p>
              Material Process
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-item">
          <a href="{{ route('processtimeline') }}" class="nav-link">
            <i class="fas fa-calendar-alt"></i>
            <p>
              Process Timeline
            </p>
          </a>
        </li> -->

        <!-- <li class="nav-header">WAREHOUSE</li>
        <li class="nav-item">
          <a href="{{ route('warehouse') }}" class="nav-link">
            <i class="fas fa-qrcode"></i>
            <p>
              QR Code Issuance
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('kitting') }}" class="nav-link">
            <i class="fas fa-qrcode"></i>
            <p>
              Kitting
            </p>
          </a>
        </li>

        <li class="nav-header">RAPID</li>
        <li class="nav-item">
          <a href="{{ route('rapid_acdcs') }}" class="nav-link">
            <i class="fas fa-file-pdf"></i>
            <p>
              ACDCS Reference
            </p>
          </a>
        </li> -->

<!--         <li class="nav-header">PPC</li>
        <li class="nav-item">
          <a href="{{ route('shipmentconfirmationalias') }}" class="nav-link">
            <i class="fas fa-file-excel"></i>
            <p>
              Shipment Confirmation
            </p>
          </a>
        </li> -->

         <!-- <li class="nav-header">EXPORT REPORT</li>
        <li class="nav-item">
          <a href="{{ route('exportshippingrecord') }}" class="nav-link">
            <i class="fa fa-file-excel"></i>
            <p>
              Shipment Record
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('exportpartslotrecord') }}" class="nav-link">
            <i class="fa fa-file-excel"></i>
            <p>
              Parts Lot Record
            </p>
          </a>
        </li>

        <li class="nav-header">REPORTS</li>
        <li class="nav-item">
          <a href="{{ route('ddr') }}" class="nav-link">
            <i class="fas fa-file-alt"></i>
            <p>
              Daily Defect
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('ophr') }}" class="nav-link">
            <i class="fas fa-file-alt"></i>
            <p>
              Operators Production Hourly
            </p>
          </a>
        </li> -->

        <!-- <li class="nav-header">PRODUCTION</li> -->
        <!-- <li class="nav-item">
          <a href="{{ route('materialissuancealias') }}" class="nav-link">
            <i class="fas fa-file-alt"></i>
            <p>
              Material Receiving
            </p>
          </a>
        </li> -->
<!--         <li class="nav-item">
          <a href="{{ route('c3labelprintingalias') }}" class="nav-link">
            <i class="fas fa-barcode"></i>
            <p>
              C3 Label Printing
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-item">
          <a href="{{ route('partspreparatoryalias') }}" class="nav-link">
            <i class="fas fa-clipboard-check"></i>
            <p>
              Parts Preparatory
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('scrapverificationruncard') }}" class="nav-link">
            <i class="fas fa-clipboard-check"></i>
            <p>
              Scrap Verification Runcard
            </p>
          </a>
        </li> -->
<!--         <li class="nav-item">
          <a href="{{ route('prod_runcard_new') }}" class="nav-link">
            <i class="fas fa-microscope"></i>
            <p>
              Final Visual
            </p>
          </a>
        </li>
 --><!-- 
        <li class="nav-item">
          <a href="{{ route('oqclotapplication_new') }}" class="nav-link">
            <i class="fas fa-file-alt"></i>
            <p>
              OQC Lot Application
            </p>
          </a>
        </li> -->

       <!--  <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-cogs"></i>
            <p>
              Production
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview"> -->
            <!-- <li class="nav-item">
              <a href="{{ route('prod_runcard') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Runcard</p>
              </a>
            </li> -->
            <!-- <li class="nav-item">
              <a href="{{ route('prod_runcard_new') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>FVI</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('oqclotapplication_new') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>OQC Lot Application</p>
              </a>
            </li> -->
            <!-- <li class="nav-item">
              <a href="{{ route('defect_escalation') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Defect Escalation</p>
              </a>
            </li> -->
         <!--  </ul>
        </li> -->
        
        <!-- <li class="nav-item">
          <a href="{{ route('oqcvir_pts') }}" class="nav-link">
            <i class="fas fa-microscope"></i>
            <p>
              OQC VIR
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('packing_pts') }}" class="nav-link">
            <i class="fas fa-archive"></i>
            <p>
              Packing Confirmation
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('packinginspection_pts') }}" class="nav-link">
            <i class="fas fa-search"></i>
            <p>
              Prel. Packing Inspection
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('supervisorvalidation_pts') }}" class="nav-link">
            <i class="fas fa-check-square"></i>
            <p>
              Supervisor Validation
            </p>
          </a>
        </li>

        <li class="nav-item">
          <a href="{{ route('finalpackinginspection_pts') }}" class="nav-link">
            <i class="fas fa-box-open"></i>
            <p>
              Final QC Packing Inspection
            </p>
          </a>
        </li> -->

         <!-- <li class="nav-item">
          <a href="{{ route('qc_shipping_inspection') }}" class="nav-link">
            <i class="fas fa-truck"></i>
            <p>
              QC Shipping Inspection
            </p>
          </a>
        </li> -->

        <!-- <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-qrcode"></i>
            <p>
              Label Printing
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('accessoryalias') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Accessory Tag</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('dlabelprintingalias') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>D Label</p>
              </a>
            </li> -->
<!--             <li class="nav-item">
              <a href="{{ route('master_box_quantity_details') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Master Box Quantity Details</p>
              </a>
            </li> -->
          <!-- </ul>
        </li> -->
<!--         <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-qrcode"></i>
            <p>
              DLABEL
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{ route('dlabelprintingalias') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>DLABEL Printing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{ route('dlabelcheckeralias') }}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>DLABEL Checker</p>
              </a>
            </li>
          </ul>
        </li> -->
   <!--      <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="fas fa-dolly"></i>
            <p>
              Shipping
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="{{route('shippingoperator')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Shipping Operator</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="{{route('shippinginspector')}}" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Shipping Inspector</p>
              </a>
            </li>
          </ul>
        </li> -->
        <!-- <li class="nav-item">
          <a href="../calendar.html" class="nav-link">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>
              Calendar
              <span class="badge badge-info right">2</span>
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-item">
          <a href="../gallery.html" class="nav-link">
            <i class="nav-icon far fa-image"></i>
            <p>
              Gallery
            </p>
          </a>
        </li> -->
        <!-- <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon far fa-envelope"></i>
            <p>
              Mailbox
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="../mailbox/mailbox.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inbox</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../mailbox/compose.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Compose</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="../mailbox/read-mail.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Read</p>
              </a>
            </li>
          </ul>
        </li> -->
        <!-- <li class="nav-item">
          <a href="{{ route('warehouse') }}" class="nav-link">
            <i class="fas fa-warehouse"></i>
            <p>
              Warehouse
            </p>
          </a>
        </li> -->
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>