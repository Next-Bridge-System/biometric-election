<?php

if (!function_exists('getFingerName')) {
    function getFingerName($id)
    {
        $finger = NULL;
        switch ($id) {
            case $id == 1:
                $finger = 'LEFT THUMB';
                break;
            case $id == 2:
                $finger = 'LEFT INDEX FINGER';
                break;
            case $id == 3:
                $finger = 'LEFT MIDDLE FINGER';
                break;
            case $id == 4:
                $finger = 'LEFT RING FINGER';
                break;
            case $id == 5:
                $finger = 'LEFT LITTLE FINGER';
                break;
            case $id == 6:
                $finger = 'RIGHT THUMB';
                break;
            case $id == 7:
                $finger = 'RIGHT INDEX FINGER';
                break;
            case $id == 8:
                $finger = 'RIGHT MIDDLE FINGER';
                break;
            case $id == 9:
                $finger = 'RIGHT RING FINGER';
                break;
            case $id == 10:
                $finger = 'RIGHT LITTLE FINGER';
                break;
            default:
                break;
        }
        return $finger;
    }
}
