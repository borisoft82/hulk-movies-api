<?php

namespace App\Http\Controllers\Api\V1;

use App\Filters\CategoryFilter;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Repositories\CategoryRepository;
use App\Http\Requests\CategoryAddRequest;
use App\Services\Category\ResponseAndLogService;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private ResponseAndLogService $responseAndLog
        )
    {
        $this->middleware('auth:api');
    }

    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->getAll();
        return $this->responseAndLog->categoryCollection($categories);
    }

    public function show(int $id): JsonResponse
    {
        $category = $this->categoryRepository->find($id);
        return $this->responseAndLog->categoryModel($category);
    }

    public function store(CategoryAddRequest $request): JsonResponse
    {
        $response = $this->categoryRepository->createCategory($request);
        return $this->responseAndLog->categoryModelCreated($response);
    }

    public function update(int $id, CategoryUpdateRequest $request): JsonResponse
    {
        $response = $this->categoryRepository->updateCategory($id, $request);
        return $this->responseAndLog->categoryModelUpdated($response);
    }

    public function destroy(int $id): JsonResponse
    {
        $response = $this->categoryRepository->deleteCategory($id);
        return $this->responseAndLog->categoryModelDeleted($response);
    }

    public function filter(CategoryFilter $filters) {
        $categories = Category::filter($filters)->paginate();
        return $this->responseAndLog->categoryCollection($categories);
    }

}
