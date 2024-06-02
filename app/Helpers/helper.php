<?php

use App\Utility\Common;

if (!function_exists('sendJsonResponse')) {
     function sendJsonResponse($response)
     {
          return Common::sendJsonResponse($response);
     }
}
