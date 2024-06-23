<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class HtmlController extends Controller
{
     public function privacyPolicy()
     {
          return view('html-public-pages.privacy-policy');
     }

     public function contactUs()
     {
          return view('html-public-pages.contact-us');
     }

     public function demo()
     {
          $currentDate = Carbon::now()->format('Y-m-d');

          $users = User::where('role_id', 2)
               ->where('subcription_end', '<', $currentDate)
               ->limit(10)
               ->get();

          foreach ($users as $user) {
               $token = $user->token;
               JWTAuth::setToken($token)->invalidate();
          }

          return "OK";
     }
}
