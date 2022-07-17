<?php

require_once __DIR__."/Provider.class.php";

class Facebook extends Provider implements Specific_provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName 		= "Facebook";
		$this->authorization_url	= "https://www.facebook.com/v2.10/dialog/oauth";
        $this->access_token_url	    = "https://graph.facebook.com/v2.10/oauth/access_token";
        $this->access_user_url	    = "https://graph.facebook.com/v2.10/me";
		$this->protocol				= "GET";
    }

	public function get_formated_user($token)
	{
		$user = json_decode($this->get_user($token), true);

		return [
			"id" => $user["id"],
			"name" => $user["name"]
		];
	}
}