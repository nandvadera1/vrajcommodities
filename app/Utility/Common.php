<?php

namespace App\Utility;

use App\Utility\Crypt\RSA as Crypt_RSA;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Common
{
    public $formatter;

    public static $photoUrl = '';

    public static $videoUrl = '';

    public static $profileUrl = '';

    public static $defaultProfileUrl = '';

    public static $defaultProfileUrl500 = '';

    public static $profileUrl500 = '';

    public static $flagUrl = '';

    public static $originUrl = '';

    public static $baseUrl = '';

    public static $Url = '';

    /**
     * Generate session token
     *
     * Generate unique md5 hashed string
     *
     * @param null
     * @return string
     */
    public static function generateToken()
    {
        $time = time();
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789' . "$time";
        $shuffled = str_shuffle($str);

        return md5($shuffled);
    }

    /**
     * Generate session token
     *
     * Generate unique md5 hashed string
     *
     * @param null
     * @return string
     */
    public static function generateOTP($limit)
    {

        $str = '0123456789';
        $shuffled = str_shuffle(substr($str, 0, $limit));

        return $shuffled;
    }

    /**
     * Generate Unique string
     *
     * @param limit integer
     * @return string
     */
    public static function uniqueString($limit)
    {
        $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
        $shuffled = str_shuffle($alphabet);
        $str = substr($shuffled, 0, $limit);

        return $str;
    }

    public static function sendJsonResponse($response)
    {
        if ($response['status_code'] == 200) {
            $responseStr = ResponseFormatter::responseSuccess($response);
        } elseif ($response['status_code'] == 500) {
            $responseStr = ResponseFormatter::responseServerError($response);
        } elseif ($response['status_code'] == 401) {
            $responseStr = ResponseFormatter::responseUnauthorized($response);
        } elseif ($response['status_code'] == 400) {
            $responseStr = ResponseFormatter::responseBadRequest($response);
        } elseif ($response['status_code'] == 406) {
            $responseStr = ResponseFormatter::responseNotAcceptable($response);
        } elseif ($response['status_code'] == 404) {
            $responseStr = ResponseFormatter::responseDataNotFound($response);
        } elseif ($response['status_code'] == 407) {
            $responseStr = ResponseFormatter::responseForRestore($response);
        } elseif ($response['status_code'] == 308) {
            $responseStr = ResponseFormatter::responseForPermanentlyRedirect($response);
        } elseif ($response['status_code'] == 202) {
            $responseStr = ResponseFormatter::responseForBankVerificationInProcess($response);
        } elseif ($response['status_code'] == 203) {
            $responseStr = ResponseFormatter::responseForAadhaarVerificationInProcess($response);
        }

        return \Illuminate\Support\Facades\Response::json($responseStr)->header('Content-Type', 'application/json');
    }

    /**
     * Convert to Camel Case
     *
     * Converts array keys to camelCase, recursively.
     *
     * @param  array  $array  Original array
     * @return array
     */
    protected static function convertToCamelCase($array)
    {
        $converted_array = [];
        foreach ($array as $old_key => $value) {
            if (is_array($value)) {
                $value = self::convertToCamelCase($value);
            } elseif (is_object($value)) {
                if ($value instanceof Model) {
                    $value = $value->toArray();
                } elseif (method_exists($value, 'toArray')) {
                    $value = $value->toArray();
                } else {
                    $value = (array) $value;
                }

                $value = self::convertToCamelCase($value);
            }
            $converted_array[Str::camel($old_key)] = $value;
        }

        return $converted_array;
    }

    /**
     * This method is used to make GET request using curl
     *
     * @param  type  $url
     * @return type
     */
    public static function httpCurlGet($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);

        curl_close($ch);

        return $output;
    }

    public static function encryptToken($dataString, $orgId = null)
    {
        $encryptedString = null;

        if (isset($dataString)) {
            if (is_array($dataString) || is_object($dataString)) {
                $dataString = json_encode($dataString);
            }
            $secret_key = env('JWT_TOKEN_PUBLIC_KEY', '0123456789abcdef0123456789abcdef');
            $secret_iv = env('JWT_TOKEN_SYSTEM_KEY', 'abcdef9876543210abcdef9876543210');
            $encrypt_algo = env('JWT_TOKEN_ALGORITHM', 'AES-128-CBC');
            $encrypt_method = env('JWT_TOKEN_ENC_METHOD', 'OPENSSL_ZERO_PADDING');

            $secret_key = hex2bin($secret_key);
            $secret_iv = hex2bin($secret_iv);
            $encryptedString = openssl_encrypt($dataString, $encrypt_algo, $secret_key, 0, $secret_iv);
        }

        return $encryptedString;
    }

    public static function decryptToken($dataString, $orgId = null)
    {
        $decrypted = '';
        if (isset($dataString)) {
            $secret_key = env('JWT_TOKEN_PUBLIC_KEY', '0123456789abcdef0123456789abcdef');
            $secret_iv = env('JWT_TOKEN_SYSTEM_KEY', 'abcdef9876543210abcdef9876543210');
            $encrypt_algo = env('JWT_TOKEN_ALGORITHM', 'AES-128-CBC');
            $encrypt_method = env('JWT_TOKEN_ENC_METHOD', 'OPENSSL_ZERO_PADDING');

            $secret_key = hex2bin($secret_key);
            $secret_iv = hex2bin($secret_iv);
            $decrypted = openssl_decrypt($dataString, $encrypt_algo, $secret_key, OPENSSL_ZERO_PADDING, $secret_iv);
            $decrypted = trim($decrypted);
        }

        return $decrypted;
    }

    public static function encryptData($data)
    {
        $path = realpath('../../data_aggregation/public');

        $public_key = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAhd+eFgqM3XZqcfL6IahBP4aORO0Vm+Pj8cyGWTkCrVlu0ZvWgxBqEXMf3lAXOHjbUGWMIn1T9wviorynxzAptCTo4pMyzcNASAQCAVk05QXoPiZ/fZE6UNW1YT+4dFK+MG0eajvHRGofq/y0EImee9FiqB20O8H64wPj3/osdVbScZxbhJkvRX3ca8FmToPS4hObgTejFlNLdMZlL+qJcQuC+xGxlWVpxGYeKc+Iq8oqrmBGPFIoNsUFYgW3TzGO+uh33Z/WL0mQ3rzmKNcmuZ/o0cZnFvPGZaMVz33yH/lNRuKfQYoPkUDfjwGJAPNuYj3+TOtNa5pcjpCg++dOJQIDAQAB';

        $keyAlias = '03252020_1';
        $rsa = new Crypt_RSA();
        $rsa->loadKey($public_key);
        $rsa->setPublicKey($public_key);

        $key = $rsa->getPublicKey();

        openssl_public_encrypt($data, $encrypted, $key, OPENSSL_PKCS1_PADDING);

        return $keyAlias . ':' . bin2hex($encrypted);
    }

    public static function customPagination($response)
    {
        $pagination['paginate_data'] = $response->items();

        $pagination['meta'] = [
            'current_page' => $response->currentPage(),
            'from' => $response->firstItem(),
            'last_page' => $response->lastPage(),
            'path' => $response->resolveCurrentPath(),
            'per_page' => $response->perPage(),
            'to' => $response->lastItem(),
            'total' => $response->total(),
        ];

        $pagination['links'] = [
            'first' => $response->url(1),
            'last' => $response->url($response->lastPage()),
            'prev' => $response->previousPageUrl(),
            'next' => $response->nextPageUrl(),
        ];

        return $pagination;
    }

    public static function s3Urls()
    {
        self::$photoUrl = Storage::disk('local');
        self::$originUrl = Storage::disk('s3')->url('webp/');
        self::$videoUrl = Storage::disk('s3video')->url('');
        self::$baseUrl = Storage::disk('s3_resources')->url('');
        self::$profileUrl = Storage::disk('s3')->url('120x120/webp/');
        self::$profileUrl500 = Storage::disk('s3')->url('500x500/webp/');
        self::$defaultProfileUrl = Storage::disk('s3')->url('120x120/webp/default_profile_picture.png');
        self::$defaultProfileUrl500 = Storage::disk('s3')->url('500x500/webp/default_profile_picture.png');
        self::$flagUrl = Storage::disk('s3_resources')->url('country/flags/');
        self::$Url = Storage::disk('s3')->url('');
    }

    public static function getAspectRatio(int $width, int $height)
    {
        $greatestCommonDivisor = static function ($width, $height) use (&$greatestCommonDivisor) {
            return ($width % $height) ? $greatestCommonDivisor($height, $width % $height) : $height;
        };

        $divisor = $greatestCommonDivisor($width, $height);

        return $width / $divisor . ':' . $height / $divisor;
    }
}
