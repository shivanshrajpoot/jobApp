<?php

namespace App\Http\Controllers\Api\v1;

use JWTAuth;
use App\Services\UserService;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Api\ApiController;
use App\Models\User;

class UserController extends ApiController {

    function __construct(UserService $userService)
    {
        parent::__construct();

        $this->userService = $userService;
    }

    /**
     * Checks Configuration Of App
     * 
     * @return JsonResponse
     */
    
    public function configuration()
    {   
        return response()->success([
            'minimum_version' => config('app.minimum_app_version'),
            'current_version' => config('app.current_app_version')
        ]);
    }

    /**
     * Registers User
     * 
     * @return JsonResponse
     */
    
    public function register()
    {
        $inputs = request()->all();

        $user = $this->userService->registerUser($inputs);

        $token = JWTAuth::fromUser($user);

        return response()->success(
            [
                'token' => $token, 
                'name' => $user->name,
                'type' => $user->type,
            ]
        );
    }

    /**
     * Login User
     * 
     * @return JsonResponse
     */
    public function signIn()
    {
        $inputs = request()->all();

        $user = $this->userService->validateAndGetUser($inputs);

        $token = JWTAuth::fromUser($user);

        return response()->success(
            [
                'token' => $token, 
                'name' => $user->name,
                'type' => $user->type,
                'email' => $user->email,
            ]
        );
    }

    /**
     * Updates Password for User
     * 
     * @return JsonResponse
     */
    public function updatePassword()
    {
        $inputs = request()->all(;

        $user = JWTAuth::parseToken()->authenticate();

        $user = $this->userService->updateUserPassword($user, $inputs);   

        return $this->respondWithItem($user, new UserTransformer);
    }

    /**
     * Updates Password for User using OTP
     * 
     * @return JsonResponse
     */
    public function updatePasswordWithOtp()
    {
        $inputs = request()->all();

        $user = $this->userService->updateUserPassword(NULL, $inputs);

        return $this->respondWithItem($user, new UserTransformer);
    }

    /**
     * Resets Password for User
     * 
     * @return JsonResponse
     */
    public function resetPassword()
    {
        $inputs = request()->all();

        $user = $this->userService->resetUserPassword($inputs);

        return $this->respondWithItem($user, new UserTransformer);
    }
}