<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallets = \App\Models\Wallet::with('divisi')->get();
        return view('wallet.index', compact('wallets'));
    }
}
