<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->userService->getUsers($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->userService->createUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->userService->getUserById($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->userService->updateUser($request, $id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->userService->patchUser($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->userService->deleteUser($id);
    }

    /**
     * Permanent ban a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permaBan(Request $request, $id)
    {
        return $this->userService->permaBan($request, $id);
    }

    /**
     * Unban a user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unBan(Request $request, $id)
    {
        return $this->userService->unBan($request, $id);
    }

    /**
     * Return Current authenticate user information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        $authUserid = $request->user()->id;

        return $this->userService->getUserById($request, $authUserid);
    }

    /**
     * Allow to create or update user avatar.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(Request $request, $id)
    {
        $authUserid = $request->user()->id;

        return $this->userService->updateAvatar($request, $id, $authUserid);
    }

    /**
     * Allow to temporary ban user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function tempUserBan(Request $request, $id)
    {
        return $this->userService->tempBanUser($request, $id);
    }

    /**
     * Allow to desactivate user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function disableUser(Request $request, $id)
    {
        return $this->userService->disableUser($request, $id);
    }
}
