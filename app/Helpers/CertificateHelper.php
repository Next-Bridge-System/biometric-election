<?php

if (!function_exists('get_lawyer_request_voucher_no')) {
    function get_lawyer_request_voucher_no($payment_id)
    {
        $vocuherNo = 4000000000000 + $payment_id;
        return $vocuherNo;
    }
}
