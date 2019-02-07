<?php

namespace SocialiteProviders\DenGro;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'DENGRO';

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['user-read'];

    /**
     * {@inheritdoc}
     */
    protected $fields = ['id', 'email', 'first_name', 'last_name', 'name', 'base_url'];

    /**
     * {@inheritdoc}
     */
    public static function additionalConfigKeys()
    {
        return ['base_url'];
    }

    /**
     * {@inheritdoc}
     */
    protected function getBaseUrl()
    {
        // $this->getConfig('base_url') is not working, using Laravel config instead
        return app('config')->get('services.dengro.base_url') ?? 'https://id.dengro.com';
    }

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            $this->getBaseUrl().'/oauth/authorize', $state
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->getBaseUrl().'/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post($this->getBaseUrl().'/api/details', [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'email'    => $user['email'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}
