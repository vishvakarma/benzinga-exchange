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
use Illuminate\Support\Facades\Http;

class StockController extends Controller
{
    private function getQuote($symbol)
    {
        $apiKey = env('BENZINGA_API_KEY');echo"<br>";
        $url = "https://api.benzinga.com/api/v2/quoteDelayed?token={$apiKey}";
        $response = Http::get($url);
        if ($response->failed()) return null;
        $data = $response->json();
        // benzinga returns 'quote' array
        if (empty($data['quote'][0])) return null;
        return $data['quote'][0];
    }

    public function quote($symbol)
    {
        $quote = $this->getQuote($symbol);
        if (!$quote) {
            return response()->json(['error' => 'Symbol not found'], 404);
        }
        return response()->json($quote);
    }

    public function buy(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $quote = $this->getQuote($request->symbol);

        if (!$quote) return back()->with('error', 'Invalid symbol.');

        $ask = $quote['askPrice'] ?? null;
        if (!$ask) return back()->with('error', 'No ask price available.');

        $total = $ask * $request->quantity;
        $balance = UserBalance::firstOrCreate(['user_id' => $userId], ['cash' => 100000]);

        if ($balance->cash < $total) {
            return back()->with('error', 'Insufficient funds.');
        }

        $portfolio = Portfolio::firstOrNew(['user_id' => $userId, 'symbol' => strtoupper($request->symbol)]);
        $portfolio->avg_price = $portfolio->shares
            ? ((($portfolio->shares * $portfolio->avg_price) + $total) / ($portfolio->shares + $request->quantity))
            : $ask;
        $portfolio->shares = ($portfolio->shares ?? 0) + $request->quantity;
        $portfolio->save();

        $balance->cash -= $total;
        $balance->save();

        return back()->with('success', 'Stock purchased successfully.');
    }

    public function sell(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $symbol = strtoupper($request->symbol);
        $portfolio = Portfolio::where(['user_id' => $userId, 'symbol' => $symbol])->first();

        if (!$portfolio || $portfolio->shares < $request->quantity) {
            return back()->with('error', 'Not enough shares to sell.');
        }

        $quote = $this->getQuote($symbol);
        if (!$quote) return back()->with('error', 'Invalid symbol.');

        $bid = $quote['bidPrice'] ?? null;
        if (!$bid) return back()->with('error', 'No bid price available.');

        $total = $bid * $request->quantity;
        $portfolio->shares -= $request->quantity;
        if ($portfolio->shares <= 0) $portfolio->delete(); else $portfolio->save();

        $balance = UserBalance::firstOrCreate(['user_id' => $userId], ['cash' => 100000]);
        $balance->cash += $total;
        $balance->save();

        return back()->with('success', 'Stock sold successfully.');
    }
}
