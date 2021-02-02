<?php

namespace App\Oauth;

use Illuminate\Support\Arr;
use Avdevs\Keycloak\KeycloakProvider;

class CustomKeycloakProvider extends KeycloakProvider
{
    /**
     * Get Authorization url
     *
     * @return string
     */
    private function getBaseAuthUrl()
    {
        return $this->getBaseUrlWithRealm() . '/protocol/openid-connect/auth';
    }

    /**
     * Builds the target URL.
     *
     * @param array $options
     * @return string Authorization URL
     */
    public function getTargetUrl(array $options = [])
    {
        $base = $this->getBaseAuthUrl();
        $params = $this->getAuthorizationParameters($options);
        $query = $this->getAuthorizationQuery($params);
        return $this->appendQuery($base, $query);
    }

    /**
     * @Override
     */
    public function user()
    {
        if (isset($_GET["code"])) {
            $socialUser = [];
            try {
                $token = $this->getAccessToken('authorization_code', [
                    'code' => $_GET["code"]
                ]);
            } catch (\Exception $e) {
                exit('Failed to get access token: ' . $e->getMessage());
            }

            try {
                $user = $this->getResourceOwner($token);
                if ($user) {
                    $user->token = $token->getToken();
                    $user->refreshToken = $token->getRefreshToken();
                    $socialUser = $user;
                }
            } catch (\Exception $e) {
                exit('Failed to get resource owner: ' . $e->getMessage());
            }
            return $socialUser;
        } else {
            return redirect()->back();
        }
    }
}
