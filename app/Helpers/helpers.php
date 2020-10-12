<?php

function formatRupiah($angka)
{
    $hasil_rupiah = "Rp " . number_format($angka, null, ',', '.');
    return $hasil_rupiah;
}

function cleanNumber($str)
{
    $result = preg_replace('/\D+/m', '', $str);
    return $result;
}

/**
 * @param int $number
 * @return string
 */
function numberToRomanRepresentation($number)
{
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if ($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}

/**
 * Apply date format to datetime
 *
 * @param string $datetime datetime
 * @param string $format   format
 *
 * @return string
 */
function datetimeFormat($datetime, $format = 'd M Y H:i:s')
{
    if (!empty($datetime)) {
        return date($format, strtotime($datetime));
    } else {
        return '';
    }
}
