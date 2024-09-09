<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Constants\CoreDefinitions;

trait TestLogin {

    public function testUserLoggedIn(): array
    {
        $user = Factory::factoryForModel('User')->create(); 

        $data = ['email' => $user->email, 'password' => 'password'];
        $response = $this->json('POST', CoreDefinitions::API . '/login', $data);        
        $response->assertStatus(200);
        
        return [
            'user' => $user, 
            'token' => $response['data']['authorization']['token']
        ];
    }
}