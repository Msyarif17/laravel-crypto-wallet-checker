<?php

namespace App\Http\Controllers;

use RoachPHP\Roach;
use Illuminate\Http\Request;
use App\Spiders\WalletSpider;
use Exception;

class WalletChackerController extends Controller
{
    public function check(){
        try{
            Roach::fake();
            Roach::startSpider(WalletSpider::class);
        }catch(Exception $e){
            return $e;
        }
        
    }
}
