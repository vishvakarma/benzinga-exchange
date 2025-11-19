<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Portfolio;
use App\Models\Transaction;
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

    /**
     * Get all transactions
     */
    public function getTransactions(int $limit = 50)
    {
        $userId = Auth::id();
        $transactions = Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        $balance = UserBalance::firstOrCreate(['user_id' => $userId], ['cash' => 100000]);
        return view('portfolio.transactions', ['balance' => $balance,'transactions' => $transactions]);
    }
}
