<?php

require 'Class/Factory.class.php';

$allProvider = [
    'Twitch' => [
        'clientId' => 'd6uzrutr0l667y2wupnv4vjka6rspz',
        'clientSecret' => 'y9bs8wyl8sf1u6wgweytag1z5ow6xj'
    ],
    'Discord' => [
        'clientId' => '988786626991894548',
        'clientSecret' => 'fiJS3nTn5cT0EymCPLFrGGXg3ZS8ok9Y'
    ],
    'Facebook' => [
		'clientId' => '2209558282570791',
		'clientSecret' => 'bf84e6364dacf5f7dc5356e56700ac08'
    ],
    'Oauth' => [
        'clientId' => '621f59c71bc35',
        'clientSecret' => '621f59c71bc36'
    ]
];

define('ALL_PROVIDER', $allProvider);

function login()
{
	$factory = new Factory();
	$providers = $factory->get_providers(ALL_PROVIDER);

	foreach($providers as $provider)
	{
		echo "<a href='".$provider->get_authorization_url()."?".$provider->get_http_build_query()."'>Login with ".$provider->get_provider_name()."</a><br>";
	}
}

// Exchange code for token then get user info
function callback()
{
		["code" => $code, "state" => $state] = $_GET;
        $factory = new Factory();

        $provider = $factory->get_provider($state, ALL_PROVIDER);
        $queryParams = $provider->get_build_query_token($code);
        $token = $provider->get_token($queryParams);
        $user = $provider->get_formated_user($token);

        echo "<pre>";
        var_dump($user);
        echo "</pre>";
}

$route = $_SERVER["REQUEST_URI"];
switch (strtok($route, "?")) {
    case '/login':
        login();
        break;
    case '/callback':
        callback();
        break;
    default:
        http_response_code(404);
        break;
}
