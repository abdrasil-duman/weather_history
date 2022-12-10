<?php

namespace App\Console\Commands;

use App\Models\Temperature;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Command;

class GetWeatherInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To get weather data from external resource and store in database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $temperature=new Temperature();
        $client = new Client();
        $request = new Request('GET', 'http://api.weatherapi.com/v1/current.json?key=' . env('API_KEY') . '&q=' . env('CITY'));
        $response = $client->send($request);
        $data=json_decode($response->getBody()->getContents(),true);
        $temp=($data['current']['temp_c']);
        $temperature->city=env('CITY');
        $temperature->temp=$temp;
        $temperature->save();
    }
}
