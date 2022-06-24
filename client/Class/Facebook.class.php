<?php

require_once __DIR__."/Provider.class.php";

class Facebook extends Provider
{
	public function __construct(string $clientId, string $clientSecret, string $protocol, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $protocol, $scope);

		$this->providerName 		= "Facebook";
		$this->authorization_url	= "https://www.facebook.com/v2.10/dialog/oauth";
        $this->access_token_url	    = "https://graph.facebook.com/v2.10/oauth/access_token";
        $this->access_user_url	    = "https://graph.facebook.com/v2.10/me";
    }

}