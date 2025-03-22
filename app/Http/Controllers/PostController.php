<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(Request $request)
    {
        return $this->postService->getAll($request);
    }

    public function store(Request $request)
    {
        return $this->postService->create($request);
    }

    public function show(Request $request, $id)
    {
        return $this->postService->getById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->postService->update($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->postService->patch($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->postService->delete($request, $id);
    }
}
