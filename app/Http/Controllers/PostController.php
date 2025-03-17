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
        return $this->postService->getAllPosts($request);
    }

    public function store(Request $request)
    {
        return $this->postService->createPost($request);
    }

    public function show(Request $request, $id)
    {
        return $this->postService->getPostById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->postService->updatePost($request, $id);
    }

    public function destroy($id)
    {
        return $this->postService->deletePost($id);
    }

    public function patch(Request $request, $id)
    {
        return $this->postService->patchPost($request, $id);
    }
}
