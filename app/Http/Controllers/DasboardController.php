<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DasboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard');
    }
}
