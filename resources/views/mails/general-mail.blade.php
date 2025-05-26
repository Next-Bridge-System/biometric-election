@extends('layouts.mail')

@section('content')
    <table class="w320" cellspacing="0" cellpadding="0" width="700">
        <tbody>
        <tr>
            <td class="body-padding mobile-padding">
                <table cellspacing="0" cellpadding="0" width="100%" style="padding-bottom:40px;text-align:left">
                    <tbody>
                    <tr>
                        <td class="left" >Dear <b>{{ $data['name'] }}</b>,</td>
                    </tr>
                    <tr>
                        <td class="left">{!! $data['description'] !!}</td>
                    </tr>
                    </tbody>
                </table>
                @if(isset($data['url']) &&  $data['url'] != 'javascript:void(0)')
                    <table cellspacing="0" cellpadding="0" style="width: 100%;margin-top: 10px;">
                        <tbody>
                        <tr>
                            <td class="mobile-center" align="left" style="padding:20px 0">
                                <div class="mobile-center" align="center"><a class="button-url" style="color:#fff;text-decoration: none"  href="{{ $data['url'] }}">Click Here</a></div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                @endif
            </td>
        </tr>
        </tbody>
    </table>
@endsection
