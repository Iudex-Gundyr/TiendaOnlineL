<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\DB; 

class IntranetController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function dashboard()
    {
        return view('Intranet/Principales/Dashboard');
    }
}


