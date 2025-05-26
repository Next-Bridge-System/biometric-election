@extends('layouts.admin')

@section('styles')
<style>
    @media print {
        body * {
            visibility: hidden;
        }

        #section-to-print,
        #section-to-print * {
            visibility: visible;
        }

        #section-to-print {
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
@endsection
@section('content')

<body onload="printPage()">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{__('Secure Card Data')}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        {{-- <li class="breadcrumb-item"><a href="{{url()->previous()}}" class="btn btn-dark">Back</a>
                        </li> --}}
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- jquery validation -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Print Application</h3>
                            <a href="#" class="btn btn-primary btn-sm float-right mr-1" onclick="printPage();"><i
                                    class="fas fa-print mr-1"></i>Print</a>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <table id="section-to-print" class="ml-4">
                                <tr class="text-center">
                                    <td>
                                        {{-- <img src="{{asset('public/admin/images/logo.png')}}" style="width: 100px"
                                            alt="PUNJAB BAR COUNCIL">
                                        <br> --}}
                                        <b>PUNJAB BAR COUNCIL, 9-FANE ROAD, LAHORE</b> <br>
                                        <b> Phone: 042-99214245-49, Fax 042-99214250</b> <br>
                                        <b> E-mail; info@pbbarcouncil.com,</b> <br>
                                        <b>URL: www.pbbarcouncil.com</b> <br>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <br> </td>
                                </tr>

                                <tr class="text-center">
                                    <td>Application Token # : <br>
                                        <h1>{{$application->application_token_no}}</h1>
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <td>Advocated Name: <br>
                                        <h4>{{$application->advocates_name}}</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div style="margin-bottom: 200px"></div>
                                        <div>
                                            ----------------------------------------------------------------
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</body>
@endsection

@section('scripts')
<script>
    function printPage () {
        window.print();
    }
</script>
@endsection
