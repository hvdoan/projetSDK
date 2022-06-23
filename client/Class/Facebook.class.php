<?php

require_once __DIR__."/Provider.class.php";

class Facebook extends Provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName 		= "Facebook";
		$this->authorization_url	= "https://www.facebook.com/v2.10/dialog/oauth";
	}
}