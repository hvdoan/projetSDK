<?php

abstract class Provider
{
	protected string $providerName;
	protected string $clientId;
	protected string $clientSecret;
	protected string $authorization_url;
	protected string $scope;

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
}
