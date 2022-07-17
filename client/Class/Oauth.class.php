<?php

require_once __DIR__."/Provider.class.php";

class Oauth extends Provider implements Specific_provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName 		= "Oauth";
		$this->authorization_url	= "http://localhost:8080/auth";
        $this->access_token_url	    = "http://server:8080/token";
        $this->access_user_url	    = "http://server:8080/me";
		$this->protocol				= "GET";
    }

	public function get_formated_user($token)
	{
		$user = $this->get_user($token);
	}
}