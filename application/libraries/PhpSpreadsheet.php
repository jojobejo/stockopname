<?php

require_once APPPATH . 'third_party/PhpSpreadsheet/src/Bootstrap.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PhpSpreadsheetLib
{
    public function spreadsheet()
    {
        return new Spreadsheet();
    }

    public function writer($spreadsheet)
    {
        return new Xlsx($spreadsheet);
    }
}
