<?php

    use \Firebase\JWT\JWT;

    // const SIMRS_API_URL = 'http://115.124.86.60:8888';
    const SIMRS_API_URL = 'http://localhost/simrsmandiri/api/index.php';

    function getEndPointHost(){
        return SIMRS_API_URL;
    }

    function setEndpoint($url=''){
        return getEndPointHost().$url;
    }

    function getHeaderEndPoint(){
        $endpoint = getEndPointHost().'/getdata';
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint, [
            'headers' => ['token' => getJWT()]
        ]);

        $header = json_decode($response->getBody()->getContents());

        $arr_header = array(
            'X-user' => $header->xUser,
            'X-timestamp' => $header->xTimestamp,
            'X-signature' => $header->xSignature,
        );

        return $arr_header;
    }

    function getJWT($username='urologi',$pass='uro123logi'){
        $key = "4p!5!mR5";
        $payload = array(
            "user" => $username,
            "pass" => $pass
        );
        $jwt = JWT::encode($payload, $key);
//        $decoded = JWT::decode($jwt, $key, array('HS256'));

        return $jwt;
    }
