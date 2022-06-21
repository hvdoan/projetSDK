<?php

require 'Class/Factory.class.php';

define('OAUTH_CLIENT_ID', '621f59c71bc35');
define('OAUTH_CLIENT_SECRET', '621f59c71bc36');
define('FACEBOOK_CLIENT_ID', '1311135729390173');
define('FACEBOOK_CLIENT_SECRET', 'fc5e25661fe961ab85d130779357541e');
define('DISCORD_CLIENT_ID', '988786626991894548');
define('DISCORD_CLIENT_SECRET', 'fiJS3nTn5cT0EymCPLFrGGXg3ZS8ok9Y');
define('TWITCH_CLIENT_ID', 'd6uzrutr0l667y2wupnv4vjka6rspz');
define('TWITCH_CLIENT_SECRET', 'y9bs8wyl8sf1u6wgweytag1z5ow6xj');

$allProvider = [
    'twitch' => [
        'clientId' => 'd6uzrutr0l667y2wupnv4vjka6rspz',
        'clientSecret' => 'y9bs8wyl8sf1u6wgweytag1z5ow6xj',
        'authorization_url' => 'https://id.twitch.tv/oauth2/authorize',
        'scope' => 'user:read:email'
    ],
    'discord' => [
        'clientId' => '988786626991894548',
        'clientSecret' => 'fiJS3nTn5cT0EymCPLFrGGXg3ZS8ok9Y',
        'authorization_url' => 'https://discord.com/api/oauth2/authorize',
        'scope' => 'identify email'
    ],
    'facebook' => [
        'clientId' => '1311135729390173',
        'clientSecret' => 'fc5e25661fe961ab85d130779357541e',
        'authorization_url' => 'https://www.facebook.com/v2.10/dialog/oauth',
        'scope' => 'public_profile,email'
    ],
    'oauth' => [
        'clientId' => '621f59c71bc35',
        'clientSecret' => '621f59c71bc36',
        'authorization_url' => 'http://localhost:8080/auth',
        'scope' => 'basic'
    ]
];

define('ALL_PROVIDER', $allProvider);
function login()
{
    $factory = new Factory();
    echo $factory->getAuthorizationUrl(ALL_PROVIDER);

    /*$queryParams= http_build_query([
        'client_id' => OAUTH_CLIENT_ID,
        'redirect_uri' => 'http://localhost:8081/callback',
        'response_type' => 'code',
        'scope' => 'basic',
        "state" => bin2hex(random_bytes(16))
    ]);
    echo "
        <form action='/callback' method='post'>
            <input type='text' name='username'/>
            <input type='password' name='password'/>
            <input type='submit' value='Login'/>
        </form>
    ";
    echo "<a href=\"http://localhost:8080/auth?{$queryParams}\">Login with OauthServer</a>";
    $queryParams= http_build_query([
        'client_id' => FACEBOOK_CLIENT_ID,
        'redirect_uri' => 'http://localhost:8081/fb_callback',
        'response_type' => 'code',
        'scope' => 'public_profile,email',
        "state" => bin2hex(random_bytes(16))
    ]);
    echo "<a href=\"https://www.facebook.com/v2.10/dialog/oauth?{$queryParams}\">Login with Facebook</a>";

    $queryParams= http_build_query([
        'client_id' => DISCORD_CLIENT_ID,
        'redirect_uri' => 'http://localhost:8081/ds_callback',
        'response_type' => 'code',
        'scope' => 'identify email',
        "state" => bin2hex(random_bytes(16))
    ]);
    echo "<a href=\"https://discord.com/api/oauth2/authorize?{$queryParams}\">Login with Disocrd</a>";

	$queryParams= http_build_query([
		'client_id' => TWITCH_CLIENT_ID,
		'redirect_uri' => 'http://localhost:8081/tw_callback',
		'response_type' => 'code',
		'scope' => 'user:read:email',
		"state" => bin2hex(random_bytes(16))
	]);
	echo "<a href=\"https://id.twitch.tv/oauth2/authorize?{$queryParams}\">Login with Twitch</a>";*/
}

// Exchange code for token then get user info
function callback()
{
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        ["username" => $username, "password" => $password] = $_POST;
        $specifParams = [
            'username' => $username,
            'password' => $password,
            'grant_type' => 'password',
        ];
    } else {
        ["code" => $code, "state" => $state] = $_GET;

        $specifParams = [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];
    }

    $queryParams = http_build_query(array_merge([
        'client_id' => OAUTH_CLIENT_ID,
        'client_secret' => OAUTH_CLIENT_SECRET,
        'redirect_uri' => 'http://localhost:8081/callback',
    ], $specifParams));
    $response = file_get_contents("http://server:8080/token?{$queryParams}");
    $token = json_decode($response, true);
    
    $context = stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$token['access_token']}"
            ]
        ]);
    $response = file_get_contents("http://server:8080/me", false, $context);
    $user = json_decode($response, true);
    echo "Hello {$user['lastname']} {$user['firstname']}";
}

function fbcallback()
{
    ["code" => $code, "state" => $state] = $_GET;

    $specifParams = [
            'code' => $code,
            'grant_type' => 'authorization_code',
        ];

    $queryParams = http_build_query(array_merge([
        'client_id' => FACEBOOK_CLIENT_ID,
        'client_secret' => FACEBOOK_CLIENT_SECRET,
        'redirect_uri' => 'http://localhost:8081/fb_callback',
    ], $specifParams));
    $response = file_get_contents("https://graph.facebook.com/v2.10/oauth/access_token?{$queryParams}");
    $token = json_decode($response, true);
    
    $context = stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$token['access_token']}"
            ]
        ]);
    $response = file_get_contents("https://graph.facebook.com/v2.10/me", false, $context);
    $user = json_decode($response, true);
    echo "Hello {$user['name']}";
}

function dscallback()
{
    ["code" => $code, "state" => $state] = $_GET;

    $specifParams = [
        'client_id' => DISCORD_CLIENT_ID,
        'client_secret' => DISCORD_CLIENT_SECRET,
        'grant_type' => 'authorization_code',
        'code' => $code,
        'redirect_uri' => 'http://localhost:8081/ds_callback',
    ];

    $context = stream_context_create([
        'http' => [
            'header' => "Content-Type: application/x-www-form-urlencoded",
            'method' => "POST",
            'content' => http_build_query($specifParams)
        ]
    ]);

    $result = file_get_contents('https://discord.com/api/oauth2/token', false, $context);
    $token = json_decode($result, true);

    $context = stream_context_create([
        'http' => [
            'header' => "Authorization: Bearer {$token['access_token']}"
        ]
    ]);

    $response = file_get_contents("https://discord.com/api/users/@me", false, $context);

    var_dump($response);
}

function twcallback()
{
	["code" => $code, "state" => $state] = $_GET;

	$specifParams = [
		'client_id' => TWITCH_CLIENT_ID,
		'client_secret' => TWITCH_CLIENT_SECRET,
		'code' => $code,
		'grant_type' => 'authorization_code',
		'redirect_uri' => 'http://localhost:8081/tw_callback',
	];

	$context = stream_context_create([
		'http' => [
			'header' => "Content-Type: application/x-www-form-urlencoded",
			'method' => "POST",
			'content' => http_build_query($specifParams)
		]
	]);

	$result = file_get_contents('https://id.twitch.tv/oauth2/token', false, $context);
	$token = json_decode($result, true);

	echo "<pre>";
	var_dump(json_decode($result, true));
	echo "</pre>";

	$context = stream_context_create([
		'http' => [
			'header' => "Authorization: Bearer {$token['access_token']}\r\nClient-Id: ".TWITCH_CLIENT_ID,

		]
	]);

	$response = file_get_contents("https://api.twitch.tv/helix/users", false, $context);

	var_dump($response);
}

$route = $_SERVER["REQUEST_URI"];
switch (strtok($route, "?")) {
    case '/login':
        login();
        break;
    case '/callback':
        callback();
        break;
    case '/fb_callback':
        fbcallback();
        break;
    case '/ds_callback':
        dscallback();
        break;
	case '/tw_callback':
		twcallback();
		break;
    default:
        http_response_code(404);
        break;
}
