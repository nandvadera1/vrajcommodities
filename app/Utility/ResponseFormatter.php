<?php

namespace App\Utility;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
     const SUCCESS = 'Success';

     const FAILURE = 'Failure';

     const ERROR = 'Error';

     public function __construct()
     {
     }

     public static function responseSuccess($params = [])
     {

          $response = [
               'STATUS' => self::SUCCESS,
               'STATUS_CODE' => Response::HTTP_OK,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => isset($params['data']) ? $params['data'] : [],
          ];

          if (isset($params['data']['meta'])) {
               $response['meta'] = $params['data']['meta'];
               unset($params['data']['meta']);
          }

          if (isset($params['data']['links'])) {
               $response['links'] = $params['data']['links'];
               unset($params['data']['links']);
          }

          if (isset($params['data']['paginate_data'])) {
               $response['data'] = $params['data']['paginate_data'];
          }

          return $response;
     }

     public static function responseFailure($params = [])
     {
          $response = [
               'STATUS' => self::FAILURE,
               'STATUS_CODE' => Response::HTTP_BAD_REQUEST,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => null,
          ];
          Log::error($response);

          return $response;
     }

     public static function responseUnauthorized($params = [])
     {
          $response = [
               'STATUS' => self::FAILURE,
               'STATUS_CODE' => Response::HTTP_UNAUTHORIZED,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => null,
          ];
          Log::error($response);

          return $response;
     }

     public static function responseBadRequest($params = [])
     {
          $response = [
               'STATUS' => self::ERROR,
               'STATUS_CODE' => Response::HTTP_BAD_REQUEST,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseNotFound($params = [])
     {
          $response = [
               'STATUS' => self::ERROR,
               'STATUS_CODE' => Response::HTTP_NOT_FOUND,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseServerError($params = [])
     {

          $response = [
               'STATUS' => self::ERROR,
               'STATUS_CODE' => Response::HTTP_INTERNAL_SERVER_ERROR,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseNotAcceptable($params = [])
     {
          $response = [
               'STATUS' => self::ERROR,
               'STATUS_CODE' => Response::HTTP_NOT_ACCEPTABLE,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseForRestore($params = [])
     {
          $response = [
               'STATUS' => self::ERROR,
               'STATUS_CODE' => Response::HTTP_PROXY_AUTHENTICATION_REQUIRED,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseDataNotFound($params = [])
     {

          $response = [
               'STATUS' => self::SUCCESS,
               'STATUS_CODE' => Response::HTTP_NOT_FOUND,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => isset($params['data']) ? $params['data'] : [],
          ];

          return $response;
     }

     public function validation_error($validator)
     {
          $success = 0;
          $message = $validator->errors()->first();
          $statusCode = Response::HTTP_NOT_FOUND;

          return $this->render($success, $message, $statusCode);
     }

     public function render($success, $message, $status, $data = null)
     {
          return [
               'STATUS' => $success,
               'STATUS_CODE' => ucfirst($message),
               'MESSAGE' => $status,
               'DATA' => $data,
          ];
     }

     public static function responseForPermanentlyRedirect($params = [])
     {
          $response = [
               'STATUS' => self::ERROR,
               'STATUS_CODE' => Response::HTTP_PERMANENTLY_REDIRECT,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseForBankVerificationInProcess($params = [])
     {
          $response = [
               'STATUS' => self::SUCCESS,
               'STATUS_CODE' => Response::HTTP_ACCEPTED,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }

     public static function responseForAadhaarVerificationInProcess($params = [])
     {
          $response = [
               'STATUS' => self::SUCCESS,
               'STATUS_CODE' => Response::HTTP_NON_AUTHORITATIVE_INFORMATION,
               'MESSAGE' => ucfirst($params['message']),
               'DATA' => (object) (isset($params['data']) ? $params['data'] : []),
          ];

          Log::error($response);

          return $response;
     }
}
