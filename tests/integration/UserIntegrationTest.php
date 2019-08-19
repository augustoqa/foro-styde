<?php

class UserIntegrationTest extends FeatureTestCase
{
    function test_a_user_create_a_post_and_subscribed_to_it()
    {
        $user = $this->defaultUser();

        $category = factory(\App\Category::class)->create();

        $post = $user->createPost([
            'title' => 'Como programar en Java',
            'content' => 'Este es el contenido',
            'category_id' => $category->id
        ]);

        $this->seeInDatabase('subscriptions', [
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
