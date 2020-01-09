<?php

namespace obsession\Http\Controllers\Api\V1\Users;

use Illuminate\Http\Request;
use obsession\Domain\Users\Users\Repositories\UsersRepositoryEloquent;
use obsession\Infrastructure\Contracts\Controllers\ControllerAbstract;
use obsession\Domain\Users\Users\Transformers\UserTransformer;
use obsession\Domain\Users\Users\User;

class UsersController extends ControllerAbstract
{

    /**
     * @var UsersRepositoryEloquent|null
     */
    protected $r_users = null;

    /**
     * UserController constructor.
     *
     * @param UsersRepositoryEloquent $r_users
     */
    public function __construct(UsersRepositoryEloquent $r_users)
    {
        $this->r_users = $r_users;
    }

    /**
     * Get User.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $user = (new UserTransformer())->transform($user);

        return response()->json($user);
    }

    /**
     * Get the authenticated User.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = (new UserTransformer())->transform($request->user());

        return response()->json($user);
    }
}