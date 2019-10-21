<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

use App\Brand;

class getCars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:brands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make a search to get overall data from brands dropdown';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client();

        //Busca marcas do select
        $crawler = $client->request('GET', 'https://seminovosbh.com.br/');

        $crawler->filter("#marca")->each(function ($selectBrand, $i) {
            $selectBrand->filter("option")->each(function ($brandData, $i) {
                if(!empty($brandData->attr('value')) and !empty($brandData->text()) and ($brandData->text() != '-'))
                {
                    Brand::updateOrCreate([
                        'id' => $brandData->attr('value'),
                        'description' => $brandData->text(),
                    ]);
                    
                    /* echo $brandData->attr('value').'-----';  
                    echo $brandData->text()."\n"; */
                }
            });
        });
    }
}