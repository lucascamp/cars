<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;

use App\Car;
use App\Optional;
use App\Photo;

class carDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:carDetails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get details from cars from the database';

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

        $cars = Car::all(); //all
        $base_url = 'https://seminovosbh.com.br';

        foreach ($cars as $car) 
        {
            $crawler = $client->request('GET', $base_url.$car->link);

            $car_data = [];

            //get price and model
            $title = $crawler->filter("#textoBoxVeiculo")->each(function ($node)
            {
                return $node->text();
            });

            if(strpos($title[0], 'R$ '))
                $title_text = explode('R$ ', $title[0]);

            if(strpos($title[0], 'valor'))
                $title_text = explode('valor', $title[0]);

            $car_data['description'] = $title_text[0] !='' ? $title_text[0]  : '';
            $car_data['price'] = ($title_text[1] !='' && isset($title_text[1])) ? $title_text[1]  : '';

            //get details 
            $details = $crawler->filter("#infDetalhes > span > ul > li")->each(function ($node, $i) {
                return $node->text();
            });
            
            $car_data['year'] = substr($details[0],0,4) != '' ? substr($details[0],0,4) : '' ;
            $car_data['km'] = explode('km', $details[1])[0] != '' ? explode('km', $details[1])[0] : '';
            $car_data['fuel'] = ($details[2] !=''&& isset($details[2])) ? $details[2]  : '';
            $car_data['doors'] = ($details[3] !=''&& isset($details[3])) ? $details[3]  : '';
            
            if(isset($details[4]))
                $car_data['color'] = ($details[4] !=''&& isset($details[4])) ? $details[4]  : '';
            else
                $car_data['color'] = '';
            
            if(isset($details[5]))
                $car_data['plate_info'] = ($details[5] !='') ? $details[5]  : '';
            else
                $car_data['plate_info'] = '';

            if(isset($details[6]))
                $car_data['trade_info'] = ($details[6] !='') ? $details[6]  : '';
            else
                $car_data['trade_info'] = '';
            
            //get optionals
            $car_data['optionals'] = $crawler->filter("#infDetalhes2 > ul > li")->each(function ($node, $i) {
                return $node->text();
            }); 

            //get notes
            $notes = $crawler->filter("#infDetalhes3")->each(function ($node, $i) {
                return $node->text()."\n";
            });

            $notes = str_replace("\n",'', trim(implode(', ',$notes)));
            $car_data['notes'] = $notes;
 
            $contact = $crawler->filter("#infDetalhes4 > .texto > ul > li ")->each(function ($node, $i) {
                return $node->text();
            });

            if(isset($contact[1]))
                $car_data['city'] = trim($contact[1]);
            else
                $car_data['city'] = '';

            $contact = str_replace("\n",'', trim(implode(', ',$contact)));
            $car_data['contact_info'] = $contact;

            //busca url de imagens dos carros
            $car_data['photos'] = $crawler->filter("dl > dt > img")->each(function ($node, $i) {
                return $node->attr('src');
            });

            //var_dump($car_data);

            $car->description = $car_data['description'];
            $car->price = $car_data['price'];
            $car->year = $car_data['year'];
            $car->city = $car_data['city'];
            $car->km = $car_data['km'];
            $car->fuel = $car_data['fuel'];
            $car->doors = $car_data['doors'];
            $car->color = $car_data['color'];
            $car->plate_info = $car_data['plate_info'];
            $car->trade_info = $car_data['trade_info'];
            $car->notes = $car_data['notes'];
            $car->contact_info = $car_data['contact_info'];
            $car->save();

            foreach ($car_data['optionals'] as $key => $optional) {
                Optional::updateOrCreate([
                    'car_id' => $car->id,
                    'description' => $optional
                ]);
            }

            foreach ($car_data['photos'] as $key => $photo) {
                Photo::updateOrCreate([
                    'car_id' => $car->id,
                    'link' => $photo
                ]);
            }
        }
    }
}
