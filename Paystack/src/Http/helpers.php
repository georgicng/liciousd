<?php

if (! function_exists('paystack')) {
    /**
     * Cart helper.
     *
     * @return \Gaiproject\Paystack\Paystack
     */
    function paystack()
    {
        return app()->make('paystack');
    }
}
