<?php

use App\Http\Controllers\Consumables;
use Illuminate\Support\Facades\Route;

Route::resource('consumablesdetails', Consumables\ConsumablesDetailsController::class, [
    'middleware' => ['auth'],
    'parameters' => ['consumabledetails' => 'consumablesdetails'],
]);
