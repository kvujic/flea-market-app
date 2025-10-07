<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Item;

class TransactionController extends Controller
{
    public function openChat(Item $item)
    {
        //abort_unless(in_array(auth()->id, [$transaction->buyer_id, $transaction->seller_id]), 403);
        //$user = auth()->user;


        return view('purchase.chat');
    }
}
