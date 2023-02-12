<?php

/*
|--------------------------------------------------------------------------
| Laravel PHP Facade/Wrapper for the Youtube Data API v3
|--------------------------------------------------------------------------
|
| Here is where you can set your key for Youtube API. In case you do not
| have it, it can be acquired from: https://console.developers.google.com
*/

$ApiKeys =[
'AIzaSyAxuliFNb92b96daoKzRFBGj4xuWYbDxCQ',
'AIzaSyD31Gkt0fuOspb3qhghJ_CYTGyJWbAh27E',
'AIzaSyAWkTz3VSnQ_UvYuzadofSOjfUjCKIEEuI',
'AIzaSyAjFV-6OZacXvrUvtSJvQ9rkTO2qSRNei4',
'AIzaSyAdRJKmHAoAC7avu8oRksmBVGG3705x9gM'
];

return [
    'key' => $ApiKeys[array_rand($ApiKeys)],
    'keys' => $ApiKeys
];
