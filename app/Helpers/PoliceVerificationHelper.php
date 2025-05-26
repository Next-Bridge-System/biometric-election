<?php

use App\PoliceVerification;

if (!function_exists('policeVerification')) {
    function policeVerification($application)
    {
        try {
            $data = [
                'PSRMS-API-KEY' => 'd8b4f95a4c931c4661471ab569ca3684f4b78078',
                'cnic' => $application->cnic_no,
            ];

            $client = new \GuzzleHttp\Client();
            $url = "https://fir.punjabpolice.gov.pk/restapi/All_Person_Search_api/allaccued";
            $request = $client->post($url, ['form_params' => $data]);
            $response = $request->getBody()->getContents();

            PoliceVerification::updateOrCreate(['application_id' => $application->id], [
                'cnic' => $data['cnic'],
                'data' => $response
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    function remove_utf8_bom($text)
    {
        $bom = pack('H*', 'EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }
}
