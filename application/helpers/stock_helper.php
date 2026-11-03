<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('hitung_qty')) {
    function hitung_qty($qty_box, $qty_pcs, $dimensi)
    {
        return ($qty_box * $dimensi) + $qty_pcs;
    }
    
}
