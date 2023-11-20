<?php

namespace App\Http\Middleware;

use App\Services\MenuServices;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'mainMenus' => MenuServices::call()->menus(),
            's_route' => request()->route(),
            'auth' => $request->user()
                ? $request->user()->select([
                    'name',
                    'role_id',
                    'avatar'
                ])->first()
                : null,
            'success' => fn () => $request->session()->get('success'),
        ]);
    }
}
