<?php


namespace App\Http\Controllers\Front;

use App\Geometry;
use App\GIS;
use App\Http\Controllers\Controller;
use App\Wilderness;
use GuzzleHttp\Client as HttpClient;


class FrontController extends Controller
{
    // Public API
    public function indexGIS() {
        return response()->json(GIS::all());
    }
    public function getImageUrls(int $perPage) {
        if ($perPage > 200) $perPage = 200;
        $baseUrl = 'https://pixabay.com/api/';
        $client = new HttpClient();
        $response = $client->request('GET', $baseUrl, [
            'query' => [
                'key' => env('PIXABAY_KEY'),
                'q' => 'forest',
                'image_type' => 'photo',
                'orientation' => 'horizontal',
                'category' => 'nature',
                'min_width' => '225',
                'min_height' => '80',
                'safesearch' => 'true',
                'per_page' => $perPage
            ]
        ]);
        //return $response->getBody()->getContents();
        return json_encode(collect(collect(json_decode($response->getBody()->getContents()))['hits'])->pluck('webformatURL')->all());
    }

    //Single Pages
    public function homePage() {
        return view('content.front.homepage');
    }
}
