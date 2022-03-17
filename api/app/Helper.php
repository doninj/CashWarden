<?php
    function serviceApi($endpoint){
        $url="https://api.covid19api.com/$endpoint";
        $crl = curl_init();

        curl_setopt($crl, CURLOPT_URL, $url);
        curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($crl);

        if(!$response){
            die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        }
        return $response;
        curl_close($crl);
    }
