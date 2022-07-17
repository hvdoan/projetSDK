<?php

require_once __DIR__."/Provider.class.php";
require_once __DIR__ . "/../Interface/Specific_provider.interface.php";

class Discord extends Provider implements Specific_provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName			= "Discord";
		$this->authorization_url	= "https://discord.com/api/oauth2/authorize";
		$this->access_token_url	    = "https://discord.com/api/oauth2/token";
		$this->access_user_url	    = "https://discord.com/api/users/@me";
		$this->protocol				= "POST";
	}

	public function get_formated_user($token)
	{
		$user = json_decode($this->get_user($token), true);

		return [
			"id" => $user["id"],
			"name" => $user["username"]
		];
	}
}