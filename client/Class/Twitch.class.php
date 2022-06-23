<?php

require_once __DIR__."/Provider.class.php";

class Twitch extends Provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName 		= "Twitch";
		$this->authorization_url	= "https://id.twitch.tv/oauth2/authorize";
	}
}