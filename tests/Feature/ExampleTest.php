<?php

namespace Tests\Feature;

use Config;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get(Config::get('APP_URL'));

        $response->assertStatus(200);
    }
}
