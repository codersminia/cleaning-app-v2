<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpParser\Builder\FunctionTest;

class TrackingController extends Controller
{
    public function index(){
        return view('Tracking');
    }
}
