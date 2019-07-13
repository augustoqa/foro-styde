<?php

use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    function test_a_user_create_a_post_and_subscribed_to_it()
    {
        $user = $this->defaultUser();

        $post = $user->createPost([
            'title' => 'Como programar en Java',
            'content' => 'Este es el contenido'
        ]);

        $this->seeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
