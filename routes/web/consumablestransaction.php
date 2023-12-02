<?php

use App\Http\Controllers\Consumables;
use Illuminate\Support\Facades\Route;

Route::resource('consumablestransaction', Consumables\ConsumablesTransactionController::class, [
    'middleware' => ['auth'],
    'parameters' => ['consumabletransaction' => 'consumablestransaction'],
]);
