<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

use Illuminate\Support\Facades\Config;
use App\Brand;
use App\Car;

class getCarsByBrand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:carsByBrand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make a search to get some data from cars';

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

        //get all brands
        $brands = Brand::all();

        foreach ($brands as $brandkey => $brand) {
            //setters to exec in run time
            $page = 1;
            $page_total = 1;
            Config::set('brand_id', $brand->id);

            $crawler_total = $client->request('GET', 'https://seminovosbh.com.br/resultadobusca/index/veiculo/carro/marca/'.$brand->description.'/ano2/2020/usuario/todos/pagina/'.$page);
            
            //set total pages to find 
            $crawler_total->filter(".total")->each(function ($node, $i) {
                $page_total = intval($node->text());
            });

            //access the pages and get the data
            for ($page; $page <= $page_total; $page++) 
            { 
                //get link of cars
                $crawler = $client->request('GET', 'https://seminovosbh.com.br/resultadobusca/index/veiculo/carro/marca/'.$brand->description.'/ano2/2020/usuario/todos/pagina/'.$page);
            
                $crawler->filter(".bg-busca > .titulo-busca > a")->each(function ($node, $id){
                    $url_parts = explode('/', $node->attr('href'));
                    array_pop($url_parts);

                    Car::updateOrCreate([
                        'id' => $url_parts[5],
                        'brand_id' => Config::get('brand_id'),
                        'description' => $node->text(),
                        'link' => implode('/', $url_parts ),
                    ]);
                    
                    //debug to see output
                        /* echo $node->text()."\n";
                        echo $node->attr('href').'-----';  */
                });
            }
        }
    }
}
