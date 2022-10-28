<?php

namespace App\Http\Controllers\Api\V1\User;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserProfileResource;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class ProfileController extends BaseController
{
    private $userService;

    public function __construct(UserService $userService)
    {
        parent::__construct();

        $this->middleware('auth')->except(['profile']);

        $this->userService = $userService;
    }

    public function profile($id)
    {
        $user = $this->userService->getById($id);
        return new UserProfileResource($user);
    }

    public function edit()
    {
        $user = $this->userService->getById(Auth::id());
        return new UserProfileResource($user);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = $this->userService->update(Auth::user(), $request);
        return new UserProfileResource($user);
    }
}
