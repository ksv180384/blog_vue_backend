<?php

namespace App\Services;

use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User\User;
use Illuminate\Support\Facades\Storage;


class UserService
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function getById($id)
    {
        $user = $this->model->query()->find($id);

        return $user;
    }

    public function update(User $user, UpdateUserRequest $request)
    {

        $user->update([
            'name' => $request->name,
            'about' => $request->about,
        ]);

        $file = $request->file('avatar');
        if($file){
            $this->removeAvatar($user);
            $avatarName = 'avatar_' . uniqid() . '.' . $file->extension();
            $path = 'users/' . $user->id . '/' . $avatarName;
            Storage::disk('public')->put($path, file_get_contents($file));
            $user->update([
                'avatar' => $avatarName,
            ]);
        }

        return $user;
    }

    public function removeAvatar(User $user)
    {

        $path = 'users/' . $user->id . '/' . $user->avatar;
        Storage::disk('public')->delete($path);
        $user->update(['avatar' => null]);

        return $user;

    }
}
