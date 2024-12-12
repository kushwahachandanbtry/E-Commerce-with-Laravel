<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function success(Request $request)
    {
        // Logic for handling successful payments
        return view('order.success'); // Create a view for the success page
    }

    public function failure(Request $request)
    {
        // Logic for handling failed payments
        return view('order.failure'); // Create a view for the failure page
    }
}
