<?php

use App\User;
use Tests\{CreatesApplication, TestsHelper};

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication, TestsHelper;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }
}
