<?php

require_once __DIR__."/Discord.class.php";
require_once __DIR__."/Twitch.class.php";
require_once __DIR__."/Facebook.class.php";
require_once __DIR__."/Oauth.class.php";

class Factory
{
    function get_providers($providers)
    {
		$providersClass = [];

    	foreach($providers as $key => $providerData)
		{
			$provider = ucfirst($key);

			if(!empty($providerData["clientId"]) && !empty($providerData["clientSecret"]))
			{
				switch($provider)
				{
					case "Discord":
						$scope = "identify email";
						break;
					case "Twitch":
						$scope = "user:read:email";
						break;
					case "Facebook":
						$scope = "public_profile,email";
						break;
					case "Oauth":
						$scope = "basic";
						break;
					default:
						$scope = "";
				}

				if(!empty($scope))
					$providersClass[] = new $provider($providerData["clientId"], $providerData["clientSecret"], $scope);
			}
		}

		return $providersClass;

//        $htmlContent = '<div>';
//
//        foreach ($providers as $key => $value)
//        {
//            $queryParam = $this->getProviderQueryParam($key, $value);
//            $htmlContent .= "<a href='".$value["authorization_url"]."?".$queryParam."'>Login with ".$key."</a><br>";
//        }
//        $htmlContent .= '</div>';
//
//        return $htmlContent;
    }

    function getProviderQueryParam($key, $provider)
    {
        return http_build_query([
            'client_id' => $provider['clientId'],
            'redirect_uri' => 'http://localhost:8081/callback',
            'response_type' => 'code',
            'scope' => $provider['scope'],
            "state" => $key . '_' .bin2hex(random_bytes(16))
        ]);
    }

    function getSpecificParams($code, $state)
	{
		$provider = ucfirst(explode("_", $state)[0]);

		new $provider();
		$specificParams = $provider->getSpecificParams($code);
		var_dump($specificParams);
	}
}
