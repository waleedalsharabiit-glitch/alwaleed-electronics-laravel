<?php

namespace App\Http\Controllers\Customer;

use App\Actions\Customer\FetchHomeDataAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(FetchHomeDataAction $action): View
    {
        return view('store.home', $action->execute());
    }
}