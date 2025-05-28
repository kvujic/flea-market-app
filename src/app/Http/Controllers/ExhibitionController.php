<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    public function sell() {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('sell');
    }

    
}
