<?php

namespace App\Http\Controllers;

use App\Models\Langganan;
use Illuminate\Http\Request;

class SpjController extends Controller
{
    public function index()
    {


        return view('spj.index');
    }
}
