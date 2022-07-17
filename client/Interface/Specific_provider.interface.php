<?php

interface Specific_provider
{
	public function __construct(string $clientId, string $clientSecret, string $scope = "");

	public function get_user($token);

	public function get_formated_user($token);
}