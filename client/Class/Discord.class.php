<?php

require_once __DIR__."/Provider.class.php";

class Discord extends Provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName			= "Discord";
		$this->authorization_url	= "https://discord.com/api/oauth2/authorize";
	}

//	function getSpecificParams($code)
//	{
//		return [
//			'client_id' => ALL_PROVIDER["discord"]["clientId"],
//			'client_secret' => ALL_PROVIDER["discord"]["clientSecret"],
//			'grant_type' => 'authorization_code',
//			'code' => $code,
//			'redirect_uri' => 'http://localhost:8081/callback'
//		];
//	}
}