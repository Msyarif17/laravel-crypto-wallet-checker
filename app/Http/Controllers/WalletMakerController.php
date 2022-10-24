<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use kornrunner\Ethereum\Address;

class WalletMakerController extends Controller
{
    private $address = "";
    private $privateKey = "";
    private $publicKey = "";
    public function __construct(){
        $a = new Address;
        $this->address = $a->get();
        $this->privateKey = $a->getPrivateKey();
        $this->publicKey = $a->getPublicKey();
        $data = [
            'address'=>'0x'.$this->address,
            'private_key'=>$this->privateKey,
            'public_key'=>$this->publicKey
        ];
        Wallet::create($data);
        return $a;
    }
    public function getAddress(){
        return $this->address;
    }
    public function getPrivateKey(){
        return $this->privateKey;
    }
    public function getPublicKey(){
        return $this->publicKey;
    }
    public function save(){
        
    }
}
