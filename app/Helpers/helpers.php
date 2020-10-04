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
