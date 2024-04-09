<?php
return [
    'paystack_popup'  => [
        'code'        => 'paystack',
        'title'       => 'Paystack',
        'description' => 'Pay with your card',
        'class'       => 'Gaiproject\Paystack\Payment\PaystackPopup',
        'active'      => true,
        'sort'        => 1,
    ],
    'paystack_redirect'  => [
        'code'        => 'paystack',
        'title'       => 'Paystack',
        'description' => 'Pay with your card',
        'class'       => 'Gaiproject\Paystack\Payment\PaystackRedirect',
        'active'      => true,
        'sort'        => 1,
    ],
];
