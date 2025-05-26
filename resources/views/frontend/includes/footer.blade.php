{{-- <nav class="navbar fixed-bottom navbar-light bg-light shadow-lg">
    <div class="container">
        <div class="col-md-12">
            <div class="text-center"><b>Copyright &copy; 2023-2024. All rights reserved. Design & Develop By
                    Octalsol.com</b></div>
        </div>
    </div>
</nav> --}}

<div class="custom-loader hidden">
    <img src="{{asset('public/admin/images/loader.gif')}}" style="width:50px;height:50px">
</div>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{asset('public/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('public/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('public/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}">
</script>
<!-- Admin App-->
<script src="{{asset('public/admin/dist/js/adminlte.min.js')}}"></script>

<!-- DataTables -->
<script src="{{asset('public/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('public/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}">
</script>
<script src="{{asset('public/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}">
</script>

<!-- DataTables Export Buttons Download -->
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>

<!-- Select2 -->
<script src="{{asset('public/admin/plugins/select2/js/select2.full.min.js')}}"></script>

<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
            $(document).on('input',function() {
                $('input[name="whatsapp_no"]').mask('0000000000');
                $('input[name="phone"]').mask('0000000000');
                $('input[name="contact"]').mask('0000000000');
                $('input[name="active_mobile_no"]').mask('0000000000');
                $('input[name="srl_mobile_no"]').mask('0000000000');
                $('input[name="cnic_no"]').mask('00000-0000000-0');
                $('input[name="srl_cnic_no"]').mask('00000-0000000-0');
                $('input[name="postal_code"]').mask('00000');
                $('.postal_code').mask('00000');
                $('input[name="rf_id"]').mask('0000000000');
                $('input[name="cnic_expiry_date"]').mask('00-00-0000');
                $('input[name="otp"]').mask('000000');
            });
        });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.0/moment.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"
    integrity="sha512-k6/Bkb8Fxf/c1Tkyl39yJwcOZ1P4cRrJu77p83zJjN2Z55prbFHxPs9vN7q3l3+tSMGPDdoH51AEU8Vgo1cgAA=="
    crossorigin="anonymous"></script>
<script src="{{asset('public/admin/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
<script src="{{asset('public/js/notification.js')}}"></script>