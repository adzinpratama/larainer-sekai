<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthServices extends UserServices
{

    public const JWT_LIFETIME = 1200;

    /**
     * Method auth
     *
     * @param array $data [explicite description]
     * @param bool $remember [explicite description]
     * @param string $guard [explicite description]
     *
     * @return object
     */
    public function auth(array $data, bool $remember = true, string $guard = 'api'): object
    {
        $auth = Auth::guard($guard)->attempt($data, $remember);

        if (!$auth) return $this->invalidResponse([
            'password' => ['wrong password']
        ]);

        return $this->authenticated($auth, Auth::guard($guard)->user());
    }

    /**
     * Method signout
     *
     * @return object
     */
    public function signout(): object
    {
        $logout = JWTAuth::invalidate(JWTAuth::getToken());
        if (!$logout) return $this->response(false, 'Invalid token');

        return $this->response(true, 'User Loging out');
    }

    /**
     * Method authenticated
     *
     * @param mixed $auth [explicite description]
     *
     * @return object
     */
    public function authenticated(mixed $auth, User $user): object
    {
        $data = [];

        if (request()->expectsJson()) $data = [
            'token_type' => 'bearer',
            'access_token' => $auth,
            'expires_in' => auth()->factory()->getTTL() * self::JWT_LIFETIME
        ];

        return $this->response(
            true,
            "Selamat Datang " . $user->name,
            $data
        );
    }
}
