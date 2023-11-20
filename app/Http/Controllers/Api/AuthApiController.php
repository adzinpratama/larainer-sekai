<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthRequest;
use App\Services\AuthServices;
use App\Traits\Http\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthApiController extends Controller
{
    use ApiController;

    public function login(
        AuthServices $service,
        AuthRequest $request
    ): JsonResponse {
        $auth = $service->auth($request->validated(), guard: 'api');
        return $this->response($auth);
    }

    public function logout(AuthServices $service): JsonResponse
    {
        return response()->json($service->signout());
    }

    public function profile(Request $request)
    {
        return $this->JsonResponse(
            true,
            data: $request->user()->toArray()
        );
    }
}
