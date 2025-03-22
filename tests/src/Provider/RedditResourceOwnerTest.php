<?php

namespace Aporat\OAuth2\Client\Test\Provider;

use Aporat\OAuth2\Client\Provider\RedditResourceOwner;
use PHPUnit\Framework\TestCase;

class RedditResourceOwnerTest extends TestCase
{
    public function testRedditResourceOwnerCreation(): void
    {
        $user_id = uniqid();
        $name = uniqid();
        $user = new RedditResourceOwner(['id' => $user_id, 'name' => $name]);

        $this->assertEquals($user_id, $user->getId());
        $this->assertEquals($name, $user->getName());
    }
}
