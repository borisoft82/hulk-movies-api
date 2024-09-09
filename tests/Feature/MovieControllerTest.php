<?php

namespace Tests\Feature;

use App\Traits\TestLogin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Constants\CoreDefinitions;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase, TestLogin;

    public function test_movie_created_by_authenticated_user(): void
    {        
        $user = $this->testUserLoggedIn();

        $category = $this->createCategoryForMovie();
        
        $data = [         
            'title' => 'Test movie title' . random_int(1, 100),   
            'storyline' => 'Test movie storyline',
            'image' => '',
            'slug' => 'test-movie-title' . random_int(1, 100),
            'director' => 'Test director',
            'writer' => 'Test writer',
            'cast' => 'Test cast',
            'user_id' => $user['user']->id,
            'category_id' => $category->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('POST', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/create', $data);

        $response->assertStatus(201);
    }

    public function test_get_all_movies_by_authenticated_user(): void
    {             
        $user = $this->testUserLoggedIn();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('GET', CoreDefinitions::API . '/movies');

        $response->assertStatus(200);
    }

    public function test_get_one_movie_by_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn();
        $movie = $this->createCategoryAndMovie($user);
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/' . $movie->id);

        $response->assertStatus(200);
    }

    public function test_movie_updated_by_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn();
        $movie = $this->createCategoryAndMovie($user);
        
        $data = [         
            'title' => 'Test movie updated' . random_int(1, 100),   
            'storyline' => 'Test movie updated',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('PUT', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/update' . '/' . $movie->id, $data);

        $response->assertStatus(200);
    }

    public function test_movie_deleted_by_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn(); 
        $movie = $this->createCategoryAndMovie($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('DELETE', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/delete' . '/' . $movie->id);

        $response->assertStatus(204);
    }

    public function test_unauthenticated_user_can_not_access_to_movie_collection(): void
    {
        $response = $this->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES);
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_specific_movie(): void
    {
        $response = $this->json('GET', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/1');
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_add_movie(): void
    {
        $response = $this->json('POST', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/create');
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_update_specific_movie(): void
    {
        $response = $this->json('PUT', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/update/1');
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_can_not_access_to_delete_specific_movie(): void
    {
        $response = $this->json('DELETE', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/delete/1');
        $response->assertStatus(401);
    }

    public function test_missing_required_fields_return_validation_errors_for_authenticated_user(): void
    {
        $user = $this->testUserLoggedIn();
            
        $data = [         
            'title' => 'Test movie title' . random_int(1, 100)
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $user['token'],
        ])->json('POST', CoreDefinitions::API . '/' . CoreDefinitions::PREFIX_MOVIES . '/create', $data);

        $response->assertStatus(422);
    }

    private function createCategoryAndMovie($user) {
        $category = $this->createCategoryForMovie();
        return Factory::factoryForModel('Movie')->create(['user_id' => $user['user']['id'], 'category_id' => $category->id]);
    }

    private function createCategoryForMovie() {
        return Factory::factoryForModel('Category')->create();
    }

}