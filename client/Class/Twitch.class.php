<?php

require_once __DIR__."/Provider.class.php";

class Twitch extends Provider implements Specific_provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		parent::__construct($clientId, $clientSecret, $scope);

		$this->providerName 		= "Twitch";
		$this->authorization_url	= "https://id.twitch.tv/oauth2/authorize";
        $this->access_token_url	    = "https://id.twitch.tv/oauth2/token";
        $this->access_user_url	    = "https://api.twitch.tv/helix/users";
		$this->protocol				= "POST";
    }

    public function get_user($token)
    {
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer {$token['access_token']}\r\nClient-Id: ".$this->clientId
            ]
        ]);

        return file_get_contents($this->access_user_url, false, $context);
    }

    public function get_formated_user($token)
	{
		$user = json_decode($this->get_user($token), true);

		return [
			"id" => $user["data"][0]["id"],
			"name" => $user["data"][0]["display_name"]
		];
	}
}