<?php

namespace App\Traits\Http;

use Illuminate\Support\Facades\Request;

trait ControllerLibs
{
    protected $_service;
    protected $_request;

    public function __construct()
    {
        if (isset($this->service)) $this->_service = new $this->service;
        if (isset($this->request)) $this->_request = new $this->request;
    }


    /**
     * Method reBackSuccess
     *
     * @param string $message [explicite description]
     *
     * @return Renderable
     */
    public function reBackSuccess(string $message)
    {
        return redirect()
            ->back()
            ->withSuccess($message);
    }

    /**
     * Method reBackError
     *
     * @param string $message [explicite description]
     *
     * @return Renderable
     */
    public function reBackError(?string $message)
    {
        return redirect()
            ->back()
            ->withErrors($message);
    }

    /**
     * Method response
     *
     * @param bool $success [explicite description]
     * @param string $message [explicite description]
     *
     * @return Renderable
     */
    public function response(object $res)
    {
        if ($res?->success) return $this->reBackSuccess($res?->message);
        else return $this->reBackError($res?->message);
    }

    /**
     * Method defaultStore
     *
     * @param $request $request [explicite description]
     * @param $all $all [explicite description]
     *
     * @return Renderable
     */
    public function defaultStore($request, $all = false)
    {
        if (!$request->id) return $this->_service->storeHandle(
            $all ? $request->all() : $request->validated()
        );
        else return $this->_service->updateHandle(
            $all ? $request->all() : $request->validated(),
            $request->id
        );
    }

    /**
     * Method defaultStoreHandle
     *
     * @param $request $request [explicite description]
     * @param bool $all [explicite description]
     * @param string $message [explicite description]
     *
     * @return Renderable
     */
    public function defaultStoreHandle($request, bool $all = false, string $message = null)
    {
        $this->defaultStore($request, $all);
        return $this->reBackSuccess($message ?? 'Data has been updated');
    }

    /**
     * Method beforeDestroy
     *
     * @param $id $id [explicite description]
     *
     * @return Renderable
     */
    public function beforeDestroy($id): void
    {
        //before destroying
    }

    /**
     * Method destroy
     *
     * @param string $id [explicite description]
     *
     * @return Renderable
     */
    public function destroy(string $id)
    {
        $this->beforeDestroy($id);
        $this->_service->removeHandle($id);
        return $this->reBackSuccess('Data has been removed');
    }
}
