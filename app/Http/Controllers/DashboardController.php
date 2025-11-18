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

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->apiKey = env('BENZINGA_API_KEY');
        $this->apiUrl = env('BENZINGA_API_URL');
    }

    private function getQuote($symbol)
    {
        $url = "{$apiUrl}/api/v2/quoteDelayed?token={$apiKey}&symbols={$symbol}";
        $response = Http::get($url);
        if ($response->failed()) return null;
        $data = $response->json();
        // benzinga returns 'quote' array
        if (empty($data[$symbol])) return null;
        return $data[$symbol];
    }

    /**
     * Display the dashboard
     */
    public function index()
    {
        $userId = Auth::id();
        $balance = UserBalance::firstOrCreate(['user_id' => $userId], ['cash' => 100000]);
        $balance = $balance->cash;
        $portfolio = Portfolio::where('user_id', $userId)->get();

         // Enrich holdings with current prices
        $enrichedHoldings = [];
        $totalValue = $balance;
        $initialCash = 100000;
        $totalProfitLoss = $totalValue - $initialCash;
        return view('dashboard.index', [
            'cashBalance' => $balance,
            'holdings' => $enrichedHoldings,
            'totalValue' => $totalValue,
            'totalProfitLoss' => $totalProfitLoss,
            'totalProfitLossPct' => ($totalProfitLoss / $initialCash) * 100,
        ]);
    }
}
