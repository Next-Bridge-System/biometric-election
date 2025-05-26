@extends('layouts.admin')

@section('content')
<livewire:admin.users.gc-show-component :user_id="$user_id" />
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function () {

            $('#f-set-disabled input[type="select"], #f-set-disabled input[type="text"], #f-set-disabled textarea, #f-set-disabled select').prop('disabled', true);
            $('#f-set-disabled input[type="select"], #f-set-disabled input[type="text"], #f-set-disabled textarea, #f-set-disabled select').prop('readonly', true);

            // Replace with the actual ID of the input you want to enable
            $('input[name="profile_image"]').removeAttr('disabled');
            $('input[name="profile_image"]').removeAttr('readonly');

        });
    </script>
@endsection
