<?php

namespace Modules\Merchant\Http\Controllers;

use App\Traits\Http\ApiController;
use App\Traits\Http\ControllerLibs;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Merchant\Http\Requests\MerchantRequest;
use Modules\Merchant\Services\MerchantServices;

class MerchantController extends Controller
{
    use ControllerLibs;

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(MerchantServices $service)
    {
        // return $this->JsonResponse(true, data: $service->getPagiCall());
        return inertia('Merchant::Master', [
            'merchants' => $service->getPagiCall()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(MerchantRequest $request, MerchantServices $service)
    {
        return $this->response(
            $service->createOrUpdate($request->validated())
        );
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id, MerchantServices $service): object
    {
        return $this->response($service->removeHandle($id));
    }
}
