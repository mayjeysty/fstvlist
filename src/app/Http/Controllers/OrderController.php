<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with('event')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
