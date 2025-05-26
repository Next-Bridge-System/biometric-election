<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Intimation Application - {{$application->application_token_no}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        .column {
            float: left;
            width: 50%;
            padding: 10px;
            height: 300px;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .dynamic-text {
            /* font-size: 18px;
            border: 1px solid;
            padding: 4px */
            font: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }

        .text-underline {
            text-decoration: underline;
        }

        .text-justify {
            text-align: justify;
        }

        body {
            background-image: url('{{asset('public/admin/images/watermark.png')}}');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            z-index: -999;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
            line-height: 35px;
        }

        .page-break {
            page-break-after: always;
        }

        table,
        th,
        td {
            border: 1px solid rgb(194, 189, 189);
            padding: 10px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        fieldset legend {
            color: green;
            font-weight: 600;
        }

        fieldset.border {
            border: 2px solid #17af23 !important;
            border-radius: 5px !important;
        }

        .mb-4 {
            margin-bottom: 20px
        }
    </style>
</head>

<body>

    <main>
        <div style="padding-left:50px; padding-right:50px">
            <div style="text-align: center">
                <p>
                    <img src="{{asset('public/admin/images/logo.png')}}" alt="" style="width:100px"> <br>
                    <span style="font: bold;font-size: 20px ">PUNJAB BAR COUNCIL</span><br>
                    9-FANE ROAD, LAHORE, Phone:042-99214245-49, Fax 042-99214250 <br>
                    E-mail:info@pbbarcouncil.com, URL:www.pbbarcouncil.com <br>
                    Facebook: /Pbbarcouncil, Instagram: /Pbbarcouncil,Youtube: /Pbbarcouncil
                </p>
            </div>
            <div style="text-align: center">
                <h4 class="text-underline">VPP RETURN BACK SLIP</h4>
            </div>
    </main>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-body">
                            <div class="row">
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th>Profile Picture:</th>
                                        <td>
                                            @if (isset($application->profile_image_url) &&
                                            Storage::exists('storage/app/public/'.$application->profile_image_url))
                                            <img src="{{asset('storage/app/public/'.$application->profile_image_url)}}"
                                                alt="" style="width: 180px; height: 180px">
                                            @else No file attached @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Lawyer Name:</th>
                                        <td>{{getLawyerName($application->id)}}</td>
                                    </tr>
                                    <tr>
                                        <th>S/o, D/o, W/o:</th>
                                        <td>{{$application->so_of ?? 'N/A'}}</td>
                                    </tr>
                                    <tr>
                                        <th>VPP Return Back Date:</th>
                                        <td>{{date('d-m-Y h:i a',strtotime($application->vppost->vpp_return_back_at))}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Signature:</th>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
