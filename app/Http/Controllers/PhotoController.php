<?php

namespace App\Http\Controllers;

use App\Services\PhotoService;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    private $photoService;

    public function __construct(PhotoService $photoService)
    {
        $this->photoService = $photoService;
    }

    public function index(Request $request)
    {
        return $this->photoService->getAll($request);
    }

    public function show(Request $request, $id)
    {
        return $this->photoService->getById($request, $id);
    }

    public function store(Request $request)
    {
        return $this->photoService->create($request, 'image_url');
    }

    public function update(Request $request, $id)
    {
        return $this->photoService->update($request, $id, 'image_url');
    }

    public function patch(Request $request, $id)
    {
        return $this->photoService->patch($request, $id, 'image_url');
    }

    public function destroy(Request $request, $id)
    {
        return $this->photoService->delete($request, $id);
    }
}
