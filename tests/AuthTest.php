<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthTest extends TestCase
{
    public static $token = null;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->json('POST', '/auth', 
            ['username' => 'admin', 'password' => 'admin2000'])
            ->seeJsonStructure([
                'token',
            ])->decodeResponseJson();

        $token = $response['token'];
    }
}
