<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $data = [];
    protected $uploadFolder = 'uploads/';

    protected $rajaOngkirApiKey = null;
    protected $rajaOngkirBaseUrl = null;
    protected $rajaOngkirOrigin = null;
    protected $couriers = [
        'jne' => 'JNE',
        'pos' => 'POS Indonesia',
        'tiki' => 'Titipan Kilat'
    ];

    protected $provinces = [];

    public function __construct()
    {
        $this->rajaOngkirApiKey = config('app.rajaongkir_api_key');
        $this->rajaOngkirBaseUrl = config('app.rajaongkir_base_url');
        $this->rajaOngkirOrigin = config('app.rajaongkir_origin');
    }

    protected function loadTheme($view, $data = [])
    {
        return view('themes/' . config('app.theme') . '/' . $view, $data);
    }

    protected function rajaOngkirRequest($resource = 'province', $method = 'get', $query = null)
    {
        $response = Http::withHeaders(['key' => $this->rajaOngkirApiKey])
            ->$method($this->rajaOngkirBaseUrl . $resource, $query)
            ->json();

        if ($response['rajaongkir']['status']['code'] == 400) {
            throw new \Exception('Raja Ongkir: ' . $response['rajaongkir']['status']['description']);
        }

        return $response;
    }

    protected function getProvinces()
    {
        $provincesFile = 'provinces.txt';
        $provincesFilePath = $this->uploadFolder . 'files/' . $provincesFile;

        $isProvincesFileExist = Storage::disk('local')->exists($provincesFile);

        if (!$isProvincesFileExist) {
            $response = $this->rajaOngkirRequest('province');
            Storage::disk('local')->put($provincesFilePath, json_encode($response['rajaongkir']['results']));
        }

        $getProvinces = json_decode(Storage::get($provincesFilePath), true);

        $provinces = [];
        if (!empty($getProvinces)) {
            foreach ($getProvinces as $province) {
                $provinces[$province['province_id']] = $province['province'];
            }
        }

        return $provinces;
    }

    protected function getCities($provinceId)
    {
        $citiesFile = 'cities_at_' . $provinceId . '.txt';
        $citiesFilePath = $this->uploadFolder . 'files/' . $citiesFile;

        $isCitiesFileExist = Storage::disk('local')->exists($citiesFilePath);

        if (!$isCitiesFileExist) {
            $response = $this->rajaOngkirRequest('city', ['province' => $provinceId]);
            Storage::disk('local')->put($citiesFilePath, json_encode($response['rajaongkir']['results']));
        }

        $getCities = json_decode(Storage::get($citiesFilePath), true);
        $cities = [];
        if (!empty($getCities)) {
            foreach ($getCities as $city) {
                $cities[$city['city_id']] = $city['city_name'];
            }
        }
        return $cities;
    }

    /**
     * Initiate payment gateway request object
     *
     * @return void
     */
    protected function initPaymentGateway()
    {
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('app.midtrans_server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;
    }
}
