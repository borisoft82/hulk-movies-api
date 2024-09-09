<?php

namespace Tests\Feature;

use App\Traits\TestLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Constants\CoreDefinitions;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, TestLogin;

    public function test_category_created_by_authenticated_user(): void
    {        
        $user = $this->testUserLoggedIn();
        
        $data = [         
            'name' => 'Documentary',
            'slug' => 'documentary' 
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('POST', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/create', $data);

        $response->assertStatus(201);
    }

    public function test_get_all_categories_by_authenticated_user(): void
    {             
        $user = $this->testUserLoggedIn();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES);

        $response->assertStatus(200);
    }

    public function test_get_one_category_by_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn();
        $category = $this->createCategory($user);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/' . $category->id);

        $response->assertStatus(200);
    }

    public function test_category_updated_by_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn();
        $category = $this->createCategory($user);
        
        $data = [         
            'name' => 'Documentary2',
            'slug' => 'documentary-2'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('PUT', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/update' . '/' . $category->id, $data);

        $response->assertStatus(200);
    }

    public function test_category_deleted_by_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn(); 
        $category = $this->createCategory($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('DELETE', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/delete' . '/' . $category->id);

        $response->assertStatus(204);
    }

    public function test_unauthenticated_user_can_not_access_to_category_collection(): void
    {
        $response = $this->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES);
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_specific_category(): void
    {
        $response = $this->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/1');
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_add_category(): void
    {
        $response = $this->json('POST', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/create');
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_update_specific_category(): void
    {
        $response = $this->json('PUT', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/update/1');
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_delete_specific_category(): void
    {
        $response = $this->json('DELETE', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/delete/1');
        $response->assertStatus(401);
    }

    public function test_missing_required_field_return_validation_error(): void
    {
        $user = $this->testUserLoggedIn();
            
        $data = [         
            'name' => 'Documentary' 
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('POST', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_CATEGORIES . '/create', $data);

        $response->assertStatus(422);
    }

    private function createCategory() {
        return Factory::factoryForModel('Category')->create();
    }

}