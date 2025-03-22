<?php

namespace Aporat\OAuth2\Client\Provider;

use Aporat\OAuth2\Client\Provider\Exception\RedditIdentityProviderException;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Token\AccessTokenInterface;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

final class Reddit extends AbstractProvider
{
    use BearerAuthorizationTrait;

    protected ?string $userAgent = null;

    /**
     * Domain
     *
     * @var string
     */
    public string $domain = 'https://www.reddit.com/api/v1';

    /**
     * Api domain
     *
     * @var string
     */
    public string $apiDomain = 'https://oauth.reddit.com/api/v1';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl(): string
    {
        return $this->domain . '/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        return $this->apiDomain . '/access_token';
    }

    /**
     * Get provider url to fetch user details
     *
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return $this->apiDomain . '/me';
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes(): array
    {
        return ['identity'];
    }

    /**
     * @inheritdoc
     */
    public function getAccessToken($grant, array $options = []): AccessTokenInterface
    {
        return parent::getAccessToken($grant, $options);
    }

    /**
     * Check a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array $data Parsed response data
     * @return void
     *
     * @throws IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if ($response->getStatusCode() >= 400) {
            throw new RedditIdentityProviderException(
                $data['message'] ?? $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response
            );
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param array $response
     * @param AccessToken $token
     * @return RedditResourceOwner
     */
    protected function createResourceOwner(
        array $response,
        AccessToken $token
    ): RedditResourceOwner {
        return new RedditResourceOwner($response);
    }

    /**
     * Returns the default headers used by this provider.
     *
     * @return array
     */
    protected function getDefaultHeaders(): array
    {
        return [
            'User-Agent' => $this->userAgent,
            'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret),
        ];
    }
}
