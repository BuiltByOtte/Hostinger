<?php

use Illuminate\Support\Facades\Route;
use Hostinger\\Extensions\\Gateways\PayPal_IPN\PayPal_IPN;

Route::post('/extensions/paypal_ipn/notify', [PayPal_IPN::class, 'notify'])->name('extensions.gateways.paypal_ipn.notify');
