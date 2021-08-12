<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Carbon\Carbon;

class CurrencyController extends Controller
{
    public function index()
    {
        return view(
            'index',
            [
                'codes' => Currency::rates()->latest()
                    ->source('crypto')
                    ->symbols(['BTC', 'BNB', 'XRP', 'DOT', 'LINK', 'LTC', 'KYD', 'USD', 'AUD', 'UKP', 'NZD', 'ETH', 'EUR', 'RUB'])
                    ->withoutVerifying()->get()
            ]
        );
    }

    public function convert(Request $request)
    {
        $request->validate([
            'amount' => 'numeric|min:0',
            'from' => 'required',
            'to' => 'required'
        ]);

        $currentTime = Carbon::now();

        $converted = Currency::convert()
            ->from($request->from)
            ->to($request->to)

            ->amount($request->amount)
            // ->round(2)
            ->withoutVerifying()
            ->throw()
            ->get();

        return back()->with([
            'conversion' => $request->amount . ' ' . $request->from . ' = ' . $converted . " " . $request->to,
            'time' => $currentTime->toDateTimeString(),
            'amount' => $request->amount,
            'from' => $request->from,
            'to' => $request->to,
            // 'curr' => expandSymbol($request->to)
        ]); //redirects user to previous webpage. 
    }
}
