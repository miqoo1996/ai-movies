<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    public function faq()     { return view('faq'); }
    public function terms()   { return view('terms'); }
    public function privacy() { return view('privacy'); }
    public function contact() { return view('contact'); }
}
