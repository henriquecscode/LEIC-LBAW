<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPagesController extends Controller
{
    public function home() {
        return view('pages.home');
    }

    public function faqs() {
        return view('pages.faqs');
    }

    public function about_us() {
        return view('pages.about');
    }

    public function contactos() {
        return view('pages.contact');
    }
    public function admin(){
        return view('pages.admin');
    }
}
