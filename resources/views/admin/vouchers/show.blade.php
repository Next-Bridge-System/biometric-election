@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1>Manage Vouchers</h1>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Voucher Detail</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @include('admin.vouchers.includes.detail')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
