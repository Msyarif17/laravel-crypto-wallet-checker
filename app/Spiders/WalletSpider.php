<?php

namespace App\Spiders;

use Generator;
use RoachPHP\Http\Request;
use RoachPHP\Http\Response;
use RoachPHP\Spider\BasicSpider;
use RoachPHP\Spider\ParseResult;
use RoachPHP\Extensions\LoggerExtension;
use App\Http\Controllers\WalletMakerController;
use App\Models\Wallet;
use RoachPHP\Extensions\StatsCollectorExtension;
use RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware;

class WalletSpider extends BasicSpider
{
    public function setUrl(){
        
    }
    

    public array $downloaderMiddleware = [
        RequestDeduplicationMiddleware::class,
    ];

    public array $spiderMiddleware = [
        //
    ];

    public array $itemProcessors = [
        //
    ];

    public array $extensions = [
        LoggerExtension::class,
        StatsCollectorExtension::class,
    ];

    public int $concurrency = 2;

    public int $requestDelay = 1;

    /**
     * @return Generator<ParseResult>
     */
    public function parse(Response $response): Generator
    {
        $address = $response->filterXPath('//span[contains(@id, "mainaddress")]')->text();
        $balance = $response->filterXPath('//div[contains(@id, "ContentPlaceHolder1_divSummary")]/div/div/div/div[contains(@class,"card-body")]/div/div[contains(@class,"col-md-8")]')->text();
        $balance = rtrim($balance," Ether");
        
        yield $this->item([
            'address' => $address,
            'balance' => $balance,
        ]);
        if($balance != "0"){
            $data = [
                'balance' => $balance,
            ];
            $wallet = Wallet::where('address',$address)->first();
            $wallet->save($data);
            echo "\e[92mASIK CUAN\n\e[0m===================================================\n"."\e[0mADDRESS :\e[92m".$address."\n\e[0mBALANCE :\e[92m".$balance."\n\e[0m"."===================================================\n";
        }
        else{
            echo "\e[91mWALLET KOSONG\n\e[0m===================================================\n"."\e[0mADDRESS :\e[93m".$address."\n\e[0mBALANCE :\e[93m".$balance."\n\e[0m"."===================================================\n";
        }
        
        
    }
    protected function initialRequests(): array
    {
        $address = new WalletMakerController;
        $a = $address->getAddress();
        $url = "".$a;
       

        return [
            new Request(
                'GET',
                "https://etherscan.io/address/{$a}",
                [$this, 'parse']
            ),
        ];
    }
    
}
