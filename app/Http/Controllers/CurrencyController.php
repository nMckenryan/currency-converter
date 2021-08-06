<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AmrShawky\LaravelCurrency\Facade\Currency;

class CurrencyController extends Controller
{
    public function index()
    {
        return view(
            'index',
            [
                'codes' => Currency::rates()->latest()->withoutVerifying()->get()
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

        $converted = Currency::convert()
            ->from($request->from)
            ->to($request->to)
            ->amount($request->amount)
            ->round(2)
            ->withoutVerifying()
            ->throw()
            ->get();

        return back()->with([
            'conversion' => $request->amount . ' ' . $request->from . 'equals' . $converted . "" . $request->to,
            'amount' => $request->amount,
            'from' => $request->from,
            'to' => $request->to
        ]); //redirects user to previous webpage. 
    }
}
