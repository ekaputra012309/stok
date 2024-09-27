<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\PermintaanModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;

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
        $today = Carbon::today();
        $monthlyStart = $today->copy()->startOfMonth();
        $yearlyStart = $today->copy()->startOfYear();
        
        $todayIncome = Transaksi::whereDate('created_at', $today)->sum('total');
        $monthlyIncome = Transaksi::whereBetween('created_at', [$monthlyStart, $today->endOfDay()])->sum('total');
        $yearlyIncome = Transaksi::whereBetween('created_at', [$yearlyStart, $today->endOfDay()])->sum('total');

        $data = [
            'title' => 'Dashboard | ',
            'todayIncome' => $todayIncome,
            'monthlyIncome' => $monthlyIncome,
            'yearlyIncome' => $yearlyIncome,
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