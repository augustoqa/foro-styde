<?php

class ExampleTest extends FeatureTestCase
{
    function test_basic_example()
    {
        $name = 'Cesar Acual';

        $user = factory(\App\User::class)->create([
            'name' => $name,
            'email' => 'admin@styde.net'
        ]);

        $this->actingAs($user, 'api')
            ->visit('api/user')
            ->see($name)
            ->see('admin@styde.net');
    }
}
