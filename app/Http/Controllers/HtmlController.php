<?php

namespace App\Http\Controllers;

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
}