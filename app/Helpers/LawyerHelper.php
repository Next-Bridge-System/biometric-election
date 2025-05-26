<?php

use Carbon\Carbon;

if (!function_exists('getVoucherName')) {
    function getVoucherName($type)
    {
        $name = NULL;
        switch ($type) {
            case $type == 1:
                $name = 'PAKISTAN B.C';
                break;
            case $type == 2:
                $name = 'PUNJAB B.C';
                break;
            case $type == 3:
                $name = 'GROUP INSURANCE';
                break;
            case $type == 4:
                $name = 'BENEVOLENT FUND';
                break;
            case $type == 5:
                $name = 'PLJ';
                break;
            default:
                break;
        }
        return $name;
    }
}

if (!function_exists('getVoucherNo')) {
    function getVoucherNo($id)
    {
        $currentDate = Carbon::now();
        $vocuherNo = 1000000000000 + strtotime($currentDate) + $id;
        return $vocuherNo;
    }
}

if (!function_exists('getLawyerVchAmount')) {
    function getLawyerVchAmount($id)
    {
        $voucher = App\Voucher::find($id);
        $age = $voucher->age;
        $amount = 0;

        if ($voucher->application_type == 1) {
            foreach ($voucher->payments as $key => $payment) {
                if ($age >= 21 && $age <= 25) {
                    if ($payment->vch_type == 1) {
                        $amount = 100;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 200;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 10000;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }

                if ($age > 25 && $age <= 30) {
                    if ($payment->vch_type == 1) {
                        $amount = 165;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 335;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 15000;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }

                if ($age > 30 && $age <= 35) {
                    if ($payment->vch_type == 1) {
                        $amount = 165;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 335;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 20000;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }

                if ($age > 35 && $age <= 40) {
                    if ($payment->vch_type == 1) {
                        $amount = 165;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 335;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 0;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }

                if ($age > 40 && $age <= 50) {
                    if ($payment->vch_type == 1) {
                        $amount = 165;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 335;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 0;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }

                if ($age > 50 && $age <= 60) {
                    if ($payment->vch_type == 1) {
                        $amount = 165;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 335;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 0;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }

                if ($age > 60) {
                    if ($payment->vch_type == 1) {
                        $amount = 165;
                    }
                    if ($payment->vch_type == 2) {
                        $amount = 335;
                    }
                    if ($payment->vch_type == 3) {
                        $amount = 2000;
                    }
                    if ($payment->vch_type == 4) {
                        $amount = 0;
                    }
                    if ($payment->vch_type == 5) {
                        $amount = 4000;
                    }
                }
            }
        }

        if ($voucher->application_type == 2) {
            foreach ($voucher->payments as $key => $payment) {
                if ($payment->vch_type == 1) {
                    $amount = 165;
                }
                if ($payment->vch_type == 2) {
                    $amount = 335;
                }
                if ($payment->vch_type == 3) {
                    $amount = 12200;
                }
                if ($payment->vch_type == 4) {
                    $amount = 2000;
                }
                if ($payment->vch_type == 5) {
                    $amount = 500;
                }
            }
        }

        return $amount;
    }
}

if (!function_exists('getBankAccount')) {
    function getBankAccount($type)
    {
        $account = NULL;
        switch ($type) {
            case $type == 1:
                $account = '(Pakistan B.C. A/C 0042-79918974-03)';
                break;
            case $type == 2:
                $account = '(Punjab B.C. A/C 0042-79000543-03)';
                break;
            case $type == 3:
                $account = '(Group Insurance A/C 0042-79000544-03)';
                break;
            case $type == 4:
                $account = '(Benevolent Fund A/C 0042-79000545-03)';
                break;
            case $type == 5:
                $account = '(PLJ A/C 0042-79000546-03)';
                break;
            default:
                break;
        }
        return $account;
    }
}
