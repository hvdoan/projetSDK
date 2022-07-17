<?php

abstract class Provider
{
	protected string $providerName;
	protected string $clientId;
	protected string $clientSecret;
	protected string $authorization_url;
    protected string $access_token_url;
    protected string $access_user_url;
	protected string $scope;
	protected string $protocol;

	public function __construct(string $clientId, string $clientSecret, string $scope = "")
	{
		$this->clientId				= $clientId;
		$this->clientSecret			= $clientSecret;
		$this->scope 				= $scope;
	}

	public function get_provider_name()
	{
		return $this->providerName;
	}

	public function get_authorization_url()
	{
		return $this->authorization_url;
	}

	public function get_http_build_query()
	{
		return http_build_query([
			'client_id' => $this->clientId,
			'redirect_uri' => 'http://localhost:8081/callback',
			'response_type' => 'code',
			'scope' => $this->scope,
			"state" => $this->providerName . '_' .bin2hex(random_bytes(16))
		]);
	}

    public function get_build_query_token($code)
    {
        return [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => 'http://localhost:8081/callback'
        ];
    }

    public function get_token($queryParams)
    {
        $token = "";

        if ($this->protocol === "GET") {
            $response = file_get_contents($this->access_token_url . "?" . http_build_query($queryParams));
            $token = json_decode($response, true);
        } else if ($this->protocol === "POST") {
            $context = stream_context_create([
                'http' => [
                    'header' => "Content-Type: application/x-www-form-urlencoded",
                    'method' => "POST",
                    'content' => http_build_query($queryParams)
                ]
            ]);
            $result = file_get_contents($this->access_token_url, false, $context);
            $token = json_decode($result, true);
        }

        return $token;
    }

    public function get_user($token)
    {
        $context = stream_context_create([
            'http' => [
                'header' => "Authorization: Bearer {$token['access_token']}"
            ]
        ]);

        return file_get_contents($this->access_user_url, false, $context);
    }
}
