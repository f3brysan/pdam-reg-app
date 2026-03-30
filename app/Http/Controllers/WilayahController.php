<?php

namespace App\Http\Controllers;

class WilayahController extends Controller
{
    public function getKecamatan($parr)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $id = '35.20';

            $parr = substr($parr, 0, 8);            

            $response = $client->get("https://wilayah.id/api/districts/{$id}.json");
            if ($response->getStatusCode() === 200) {
                $kecamatan = json_decode($response->getBody(), true);
                $kecamatan = $kecamatan['data'];

                foreach ($kecamatan as $kecamatanData) {
                    $kecamatan[$kecamatanData['code']]['code'] = $kecamatanData['code'];
                    $kecamatan[$kecamatanData['code']]['name'] = $kecamatanData['name'];
                }

                $selectedKecamatan = $kecamatan[$parr];
                return response()->json([
                    'success' => true,
                    'data' => $selectedKecamatan
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$th->getMessage()
            ], 500);
        }
    }

    public function getKelurahan($parr)
    {
        try {
            $client = new \GuzzleHttp\Client();

            $districts = [
                ['code' => '35.20.12', 'name' => 'Barat'],
                ['code' => '35.20.10', 'name' => 'Bendo'],
                ['code' => '35.20.13', 'name' => 'Karangrejo'],
                ['code' => '35.20.14', 'name' => 'Karas'],
                ['code' => '35.20.15', 'name' => 'Kartoharjo'],
                ['code' => '35.20.05', 'name' => 'Kawedanan'],
                ['code' => '35.20.03', 'name' => 'Lembeyan'],
                ['code' => '35.20.06', 'name' => 'Magetan'],
                ['code' => '35.20.11', 'name' => 'Maospati'],
                ['code' => '35.20.16', 'name' => 'Ngariboyo'],
                ['code' => '35.20.17', 'name' => 'Nguntoronadi'],
                ['code' => '35.20.08', 'name' => 'Panekan'],
                ['code' => '35.20.02', 'name' => 'Parang'],
                ['code' => '35.20.07', 'name' => 'Plaosan'],
                ['code' => '35.20.01', 'name' => 'Poncol'],
                ['code' => '35.20.18', 'name' => 'Sidorejo'],
                ['code' => '35.20.09', 'name' => 'Sukomoro'],
                ['code' => '35.20.04', 'name' => 'Takeran'],
            ];

            $kelurahan = [];
            foreach ($districts as $district) {
                $response = $client->get("https://wilayah.id/api/villages/{$district['code']}.json");
                if ($response->getStatusCode() === 200) {
                    $kelurahanDatas = json_decode($response->getBody(), true);
                    
                    foreach ($kelurahanDatas['data'] as $kelurahanData) {
                        $kelurahan[$kelurahanData['name']]['code'] = $kelurahanData['code'];
                        $kelurahan[$kelurahanData['name']]['name'] = $kelurahanData['name'];
                    }   
                }
            }
            
            $getSelectedKelurahan = $kelurahan[$parr];

            return response()->json([
                'success' => true,
                'data' => $getSelectedKelurahan
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$th->getMessage()
            ], 500);
        }
    }
}
