<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/image-proxy', function (Request $request) {
    $url = $request->query('url');
    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
        abort(400, 'Invalid URL');
    }

    try {
        $image = file_get_contents($url);
        $headers = get_headers($url, 1);
$contentType = isset($headers['Content-Type']) ? $headers['Content-Type'] : 'image/jpeg';

return response($image)->header('Content-Type', $contentType);
    } catch (\Exception $e) {
        abort(404);
    }
});


Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

