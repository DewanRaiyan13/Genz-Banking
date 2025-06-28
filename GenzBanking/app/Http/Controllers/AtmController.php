<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ATM;
use Spatie\Geocoder\Geocoder;

class AtmController extends Controller
{
    public function index(Request $request)
{
    $lat = $request->lat ?? 23.8103;
    $lng = $request->lng ?? 90.4125;
    
    $atms = $this->getDummyAtms($lat, $lng);
    
    return view('atm.index', compact('atms', 'lat', 'lng'));
}
    private function getDummyAtms($lat, $lng)
    {
        return collect([
            [
                'id' => 1,
                'name' => 'Prime Bank ATM',
                'address' => 'Gulshan 1, Dhaka',
                'lat' => $lat + 0.005,
                'lng' => $lng + 0.005
            ],
            [
                'id' => 2,
                'name' => 'BRAC Bank ATM',
                'address' => 'Banani, Dhaka',
                'lat' => $lat - 0.003,
                'lng' => $lng + 0.007
            ],
            [
                'id' => 3,
                'name' => 'DBBL ATM Booth', // Added missing name field
                'address' => 'Dhanmondi 27, Dhaka',
                'lat' => $lat + 0.008,
                'lng' => $lng - 0.002
            ]
        ]);
    }
}