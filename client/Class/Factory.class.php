<?php

class Factory
{
    function getAuthorizationUrl($providers)
    {
        $htmlContent = '<div>';

        foreach ($providers as $key => $value)
        {
            $queryParam = $this->getProviderQueryParam($key, $value);
            $htmlContent .= "<a href='".$value["authorization_url"]."?".$queryParam."'>Login with ".$key."</a><br>";
        }
        $htmlContent .= '</div>';

        return $htmlContent;
    }

    function getProviderQueryParam($key, $provider)
    {
        return http_build_query([
            'client_id' => $provider['clientId'],
            'redirect_uri' => 'http://localhost:8081/callback',
            'response_type' => 'code',
            'scope' => $provider['scope'],
            "state" => $key . '_' .bin2hex(random_bytes(16))
        ]);


    }

}
