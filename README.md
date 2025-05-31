# Reddit Provider for OAuth 2.0 Client
[![Latest Stable Version](https://img.shields.io/packagist/v/aporat/oauth2-reddit.svg?logo=composer)](https://packagist.org/packages/aporat/oauth2-reddit)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![codecov](https://codecov.io/github/aporat/oauth2-reddit/graph/badge.svg?token=052F64LGUC)](https://codecov.io/github/aporat/oauth2-reddit)
![GitHub Actions Workflow Status](https://github.com/aporat/oauth2-reddit/actions/workflows/ci.yaml/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/aporat/oauth2-reddit.svg)](https://packagist.org/packages/aporat/oauth2-reddit)

This package provides Reddit OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use composer:

```
composer require aporat/oauth2-reddit
```

## Usage

Usage is the same as The League's OAuth client, using `\Aporat\OAuth2\Client\Provider\Reddit` as the provider.

### Authorization Code Flow

```php
$provider = new Aporat\OAuth2\Client\Provider\Reddit([
    'clientId'          => '{reddit-client-id}',
    'clientSecret'      => '{reddit-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
    'userAgent'         => 'platform:appid:version, (by /u/username)}',
]);

if (!isset($_GET['code'])) {

    // If we don't have an authorization code then get one
    $authUrl = $provider->getAuthorizationUrl();
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: '.$authUrl);
    exit;

// Check given state against previously stored one to mitigate CSRF attack
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

    unset($_SESSION['oauth2state']);
    exit('Invalid state');

} else {

    // Try to get an access token (using the authorization code grant)
    $token = $provider->getAccessToken('authorization_code', [
        'code' => $_GET['code']
    ]);

    // Optional: Now you have a token you can look up a users profile data
    try {

        // We got an access token, let's now get the user's details
        $user = $provider->getResourceOwner($token);

        // Use these details to create a new profile
        printf('Hello %s!', $user->getUsername());

    } catch (Exception $e) {

        // Failed to get user details
        exit('Oh dear...');
    }

    // Use this to interact with an API on the users behalf
    echo $token->getToken();
}
```

### Managing Scopes

When creating your Reddit authorization URL, you can specify the state and scopes your application may authorize.

```php
$options = [
    'state' => 'OPTIONAL_CUSTOM_CONFIGURED_STATE',
    'scope' => ['user_accounts','pins'] // array or string; at least 'user:email' is required
];

$authorizationUrl = $provider->getAuthorizationUrl($options);
```

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](https://github.com/aporat/oauth2-reddit/blob/master/CONTRIBUTING.md) for details.

## License

The MIT License (MIT). Please see [License File](https://github.com/aporat/oauth2-reddit/blob/master/LICENSE) for more information.
