<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $branding = CompanySetting::first();
        return view('home', [
            'favicon' => ($branding && $branding->favicon) ? asset('storage/' . $branding->favicon) : asset('buzbox/img/favicon.ico'),
            'logo' => ($branding && $branding->logo) ? asset('storage/' . $branding->logo) : asset('buzbox/img/logo-s.png'),
            'name_logo' => ($branding && $branding->name_logo) ? asset('storage/' . $branding->name_logo) : null,
        ]);
    }
}
