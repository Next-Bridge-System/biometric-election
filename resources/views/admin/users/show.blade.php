@extends('layouts.admin')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Register Users</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        {{-- <a href="{{route('users.index')}}" class="btn btn-dark btn-sm"><i
                                class="fas fa-chevron-left mr-2"></i>Back</a> --}}
                    </li>
                </ol>
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
                        <h3 class="card-title">User Detail</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th>ID</th>
                                    <td>{{$user->id}}</td>
                                    <th>Name</th>
                                    <td>{{$user->name}}</td>
                                    <th>CNIC</th>
                                    <td>{{$user->cnic_no}}</td>
                                </tr>
                                <tr>
                                    <th>Father Name</th>
                                    <td>{{$user->id}}</td>
                                    <th>Final Type</th>
                                    <td>
                                        <span class="badge badge-info">{{ strtoupper($user->register_as) }}</span>
                                    </td>
                                    <th>Email</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <fieldset class="border p-4 mt-4">
                                <legend class="w-auto d-flex align-items-center">
                                    Biometric Registration
                                    @php
                                    $biometricStatus = $user->biometric_status == 1 ? "Registered" : 'Not Registered';
                                    $badgeClass = $biometricStatus === 1 ? 'badge-success' : 'badge-warning';
                                    @endphp
                                    <span class="badge {{ $badgeClass }} ml-3">{{ $biometricStatus }}</span>


                                    @if ($user->biometric_status == 1)
                                    <a href="{{ route('biometrics.registration', $user->id) }}"
                                        class="btn btn-success btn-sm ml-3">
                                        <i class="fas fa-edit"></i> Edit Biometric
                                    </a>
                                    @else
                                    <a href="{{ route('biometrics.registration', $user->id) }}"
                                        class="btn btn-primary btn-sm ml-3">
                                        <i class="fas fa-plus"></i> Register Biometric
                                    </a>
                                    @endif

                                    @if ($user->biometric_status == 1)


                                    <a href="{{ route('biometrics.verification', $user->id) }}"
                                        class="btn btn-primary btn-sm ml-3">
                                        <i class="fas fa-fingerprint mr-1"></i> Biometric Verification
                                    </a>
                                    @endif

                                </legend>

                                <div class="stepper-card p-4">
                                    <div id="form-steps">

                                        @if ($user->webcam_image_path)
                                        <img src="{{asset('storage/app/public/'.$user->webcam_image_path)}}" alt=""
                                            style="border: 2px solid #28a745; width: 200px; height: 150px; object-fit: cover; border-radius: 4px;">
                                        @endif

                                        <div class="form-step" data-step="2">

                                            <div class="">
                                                @php
                                                $titles = [
                                                1 => 'Left Little Finger',
                                                2 => 'Left Ring Finger',
                                                3 => 'Left Middle Finger',
                                                4 => 'Left Index Finger',
                                                5 => 'Left Thumb',
                                                6 => 'Right Thumb',
                                                7 => 'Right Index Finger',
                                                8 => 'Right Middle Finger',
                                                9 => 'Right Ring Finger',
                                                10 => 'Right Little Finger',
                                                ];
                                                // Get saved finger ids for this user
                                                $savedFingers = \DB::table('biometrics')->where('user_id',
                                                $user->id)->pluck('finger_id')->toArray();
                                                @endphp

                                                @for ($i = 1; $i <= 10; $i++) @php $isSaved=in_array($i, $savedFingers);
                                                    @endphp <button data-toggle="tooltip" data-placement="top"
                                                    title="{{ $titles[$i] }}" type="button"
                                                    class="btn finger finger_{{ $i }} {{ $isSaved ? 'btn-success' : '' }}"
                                                    finger-id="{{ $i }}">
                                                    </button>
                                                    @endfor

                                                    @include('admin.biometric.hands')
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection

<link rel="stylesheet" href="{{asset('public/biometric/css/index/index.css')}}">
<link rel="stylesheet" href="{{asset('public/biometric/css/index/finger.css')}}">
@section('scripts')

<script>


</script>
@endsection