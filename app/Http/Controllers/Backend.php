<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PermintaanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Backend extends Controller
{
    public function signin()
    {
        $data = array(
            'title' => 'Login | ',
        );
        return view('backend.login', $data);
    }

    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard | ',
            'todayIncome' => 500000,
            'monthlyIncome' => 2300000,
            'yearlyIncome' => 15700000,
        ];

        return view('backend.dashboard', $data);
    }

    public function profile(Request $request)
    {
        $data = array(
            'title' => 'Profile | ',
            'user' => $request->user(),
        );
        return view('backend.profile', $data);
    }
}