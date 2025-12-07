<?php

namespace App\Http\Controllers;

use App\Models\Langganan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {


        return view('admin.index');
    }
}
