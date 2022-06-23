<?php

require_once __DIR__."/Provider.class.php";

class Oauth extends Provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName 		= "Oauth";
		$this->authorization_url	= "http://localhost:8080/auth";
	}
}