<?php

namespace Gaiproject\Theme\Http\Controllers;

class CartController extends Controller
{
    /**
     * Cart page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('licious::checkout.cart.index');
    }
}
