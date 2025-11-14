<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Portfolio;
use App\Models\UserBalance;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $balance = UserBalance::firstOrCreate(['user_id' => $userId], ['cash' => 100000]);
        $portfolio = Portfolio::where('user_id', $userId)->get();

        return view('portfolio.index', compact('portfolio', 'balance'));
    }
}
