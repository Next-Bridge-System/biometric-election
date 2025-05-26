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
            page-break-after: always !important;
        }

        table,
        th,
        td {
            border: 1px solid rgb(194, 189, 189);
            padding: 3px;
            font-size: 12px;
            text-align: left !important;
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

        .custom-image-preview {
            width: 100px;
            height: 100px
        }

        .badge {
            display: inline-block;
            padding: .25em .4em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25rem;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .badge-danger {
            color: rgb(0, 0, 0);
            background-color: #e4e3e3;
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
                <h4 class="text-underline">INTIMATION APPLICATION</h4>
            </div>
    </main>

    <section class="content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">
                        <div class="card-body">
                            @include('admin.intimations.partials.application-section')
                            @include('admin.intimations.partials.payment-section')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
