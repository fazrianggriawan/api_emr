<?php

namespace App\Http\Libraries;

use App\Models\Setting_bridging_bpjs;
use Exception;
use LZCompressor\LZString;

const ID_SETTING     = 1;
const CONFIGURATION = 'production'; // dev or production

class LibEklaim
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public static function inacbg_encrypt($data, $key)
    {
        /// make binary representasion of $key
        $key = hex2bin($key);
        /// check key length, must be 256 bit or 32 bytes
        if (mb_strlen($key, "8bit") !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        /// create initialization vector
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        $iv = openssl_random_pseudo_bytes($iv_size); // dengan catatan dibawah
        /// encrypt
        $encrypted = openssl_encrypt(
            $data,
            "aes-256-cbc",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        /// create signature, against padding oracle attacks
        $signature = mb_substr(hash_hmac(
            "sha256",
            $encrypted,
            $key,
            true
        ), 0, 10, "8bit");
        /// combine all, encode, and format
        $encoded = chunk_split(base64_encode($signature . $iv . $encrypted));
        return $encoded;
    }
    // Decryption Function
    public static function inacbg_decrypt($str, $strkey)
    {
        /// make binary representation of $key
        $key = hex2bin($strkey);
        /// check key length, must be 256 bit or 32 bytes
        if (mb_strlen($key, "8bit") !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        /// calculate iv size
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        /// breakdown parts
        $decoded = base64_decode($str);
        $signature = mb_substr($decoded, 0, 10, "8bit");
        $iv = mb_substr($decoded, 10, $iv_size, "8bit");
        $encrypted = mb_substr($decoded, $iv_size + 10, NULL, "8bit");
        /// check signature, against padding oracle attack
        $calc_signature = mb_substr(hash_hmac(
            "sha256",
            $encrypted,
            $key,
            true
        ), 0, 10, "8bit");
        if (!self::inacbg_compare($signature, $calc_signature)) {
            return "SIGNATURE_NOT_MATCH"; /// signature doesn't match
        }
        $decrypted = openssl_decrypt(
            $encrypted,
            "aes-256-cbc",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        return $decrypted;
    }

    /// Compare Function
    public static function inacbg_compare($a, $b)
    {
        /// compare individually to prevent timing attacks
        /// compare length
        if (strlen($a) !== strlen($b)) return false;
        /// compare individual
        $result = 0;
        for ($i = 0; $i < strlen($a); $i++) {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $result == 0;
    }

    public static function exec($jsonRequest)
    {
        $setting = self::getSetting('eklaim');
        $payload = self::inacbg_encrypt($jsonRequest, $setting->userkey);
        $header = array("Content-Type: application/x-www-form-urlencoded");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $setting->rest_api);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // request dengan curl
        $response = curl_exec($ch);
        // terlebih dahulu hilangkan "----BEGIN ENCRYPTED DATA----\r\n"
        // dan hilangkan "----END ENCRYPTED DATA----\r\n" dari response
        $first = strpos($response, "\n") + 1;
        $last = strrpos($response, "\n") - 1;
        $response = substr(
            $response,
            $first,
            strlen($response) - $first - $last
        );
        // decrypt dengan fungsi inacbg_decrypt
        $response = self::inacbg_decrypt($response, $setting->userkey);

        return self::response($response);
    }

    public static function response($data)
    {
        $res = json_decode($data);
        if( $res->metadata->code == 200 ){
            // hasil decrypt adalah format json, ditranslate kedalam array
            $msg = json_decode($data, true);
            // variable data adalah base64 dari file pdf
            $pdf = base64_decode($msg["data"]);
            // hasilnya adalah berupa binary string $pdf, untuk disimpan:
            file_put_contents("klaim.pdf", $pdf);
            // atau untuk ditampilkan dengan perintah:
            header("Content-type:application/pdf");
            header("Content-Disposition:attachment;filename=klaim-.pdf");
            return $pdf;
        }else{
            return $data;
        }
    }

    public static function getSetting($apiType)
    {
        $data = Setting_bridging_bpjs::where('id_setting', ID_SETTING)
            ->where('api_type', $apiType)
            ->where('configuration', CONFIGURATION)
            ->first();
        return $data;
    }
}
