<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CardOffer;

class CardOfferController extends Controller
{
    public function index() // Renamed from madari to index
    {
        $offers = CardOffer::all();
        return view('card_offers', compact('offers'));
    }
}