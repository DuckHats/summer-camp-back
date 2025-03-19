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
        return $this->photoService->getAllPhotos($request);
    }

    public function store(Request $request)
    {
        return $this->photoService->createPhoto($request);
    }

    public function show(Request $request, $id)
    {
        return $this->photoService->getPhotoById($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->photoService->updatePhoto($request, $id);
    }

    public function destroy(Request $request, $id)
    {
        return $this->photoService->deletePhoto($request, $id);
    }

    public function patch(Request $request, $id)
    {
        return $this->photoService->patchPhoto($request, $id);
    }
}
