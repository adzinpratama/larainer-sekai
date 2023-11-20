<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthRequest;
use App\Services\AuthServices;
use App\Traits\Http\ControllerLibs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    use ControllerLibs;

    public function login()
    {
        return inertia('Login');
    }

    /**
     * Method auth
     *
     * @param AuthServices $service [explicite description]
     * @param AuthRequest $request [explicite description]
     *
     * @return Renderable
     */
    public function auth(
        AuthServices $service,
        AuthRequest $request
    ) {
        $res = $service->auth(
            $request->validated(),
            request()->remember,
            'web'
        );

        if (!$res->success) return $this->reBackError(
            $res?->message
        );

        return redirect()->intended()
            ->withSuccess($res?->message);
    }

    public function logout()
    {
        Session::flush();
        return Auth::logout();
    }
}
