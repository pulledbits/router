<?php

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the controller to call when that URI is requested.
 * |
 */
Route::get('/', function () {
	 $ipv4Adresses = [
        $_SERVER['HTTP_HOST']
    ];
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        exec("ipconfig", $ipconfigData, $exitCode);
        if ($exitCode !== 0) {
            exit('failed retrieving ip adresses');
        }
        foreach ($ipconfigData as $line) {
            if (preg_match('/IPv4 Address(\.\s)+:\s(?<ipv4>\d+\.\d+\.\d+\.\d+)/', $line, $matches) === 1) {
                $ipv4Adresses[] = $matches['ipv4'];
            }
        }
    } else {
        exec("ifconfig", $ipconfigData, $exitCode);
        if ($exitCode !== 0) {
            exit('failed retrieving ip adresses');
        }
        foreach ($ipconfigData as $line) {
            if (preg_match('/inet addr:(?<ipv4>\d+\.\d+\.\d+\.\d+)/', $line, $matches) === 1) {
                $ipv4Adresses[] = $matches['ipv4'];
            }
            
        }
    }
    return view('welcome', [
        'modules' => App\Module::all(),
        'contactmomenten' => App\Contactmoment::where('starttijd', '>', date('Y-m-d 00:00:00'))->where('starttijd', '<', date('Y-m-d 23:59:00'))->get(),
        'ipv4Adresses' => $ipv4Adresses
    ]);
});

Route::post('/thema/create', 'Thema@create')->name('thema.create');
Route::post('/activiteit/create', 'Activiteit@create')->name('activiteit.create');
Route::post('/activiteit/edit/{activiteit}', 'Activiteit@edit')->name('activiteit.edit');
    
Route::get('/contactmoment/import', function () {
    return view('contactmoment.import', []);
});
Route::post('/contactmoment/import', 'Contactmoment@importFromURL');

Route::get('/contactmoment/{contactmoment}', 'Contactmoment@read');

Route::get('/feedback/{contactmoment}', function (App\Contactmoment $contactmoment) {
    if (array_key_exists('HTTPS', $_SERVER) === false) {
        $scheme = 'http';
    } else {
        $scheme = 'https';
    }

    return view('feedback', [
        'contactmoment' => $contactmoment,
        'url' => $scheme . '://' . $_SERVER['HTTP_HOST'] . '/feedback/' . $contactmoment->id . '/supply'
    ]);
});
Route::get('/feedback/{contactmoment}/supply', function (\Illuminate\Http\Request $request, App\Contactmoment $contactmoment) {
    $assetsDirectory = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets';

    $ipRating = $contactmoment->ratings()->firstOrNew([
        'ipv4' => $_SERVER['REMOTE_ADDR']
    ]);
    
    $imageStar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'star.png';
    $starData = base64_encode(file_get_contents($imageStar));
    
    $imageUnstar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'unstar.png';
    $unstarData = base64_encode(file_get_contents($imageUnstar));
    
    if ($ipRating !== null) {
        $data = [
            'rating' => $ipRating->waarde,
            'explanation' => $ipRating->inhoud
        ];
    } else {
        $data = null;
    }
    
    if ($data !== null) {
        $rating = $data['rating'];
        $explanation = $data['explanation'];
    } else {
        $rating = null;
        $explanation = null;
    }

    if ($request->has('rating')) {
        $rating = $request->input('rating');
    }
    
    return view('feedback/supply', [
        'rating' => $rating,
        'explanation' => $explanation,
        'uris' => [
            'star' => 'data: ' . mime_content_type($imageStar) . ';base64,' . $starData,
            'unstar' => 'data: ' . mime_content_type($imageUnstar) . ';base64,' . $unstarData
        ]
    ]);
});
Route::post('/feedback/{contactmoment}/supply', function (\Illuminate\Http\Request $request, App\Contactmoment $contactmoment) {
    $rating = $contactmoment->ratings()->firstOrNew([
        'ipv4' => $_SERVER['REMOTE_ADDR'],
        'waarde' => $request->rating
    ]);
    $rating->inhoud = $request->explanation;
    $rating->save();
    return 'Dankje!';
});

Route::get('/rating/{contactmoment}', function (App\Contactmoment $contactmoment) {
    $assetsDirectory = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'assets';
    $imageStar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'star.png';
    $imageUnstar = $assetsDirectory . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'unstar.png';

    return view('rating', [
        'rating' => $contactmoment->rating,
        'starData' => file_get_contents($imageStar),
        'unstarData' => file_get_contents($imageUnstar)
    ]);
});
Route::get('/qr', function () {
    $data = request('data');
    if ($data === null) {
        return abort(400);
    }
    return view('qr', [
        'data' => $data
    ]);
});
