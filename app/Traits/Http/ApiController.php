<?php

namespace App\Traits\Http;

use Illuminate\Http\JsonResponse;

trait ApiController
{

    /**
     * _service
     *
     * @var mixed
     */
    protected $_service;

    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        if (isset($this->service)) $this->_service = $this->service;
    }

    /**
     * Method response
     *
     * @param object $data [explicite description]
     *
     * @return JsonResponse
     */
    public function response(object $data): JsonResponse
    {
        return $this->JsonResponse(
            $data->success,
            $data?->message,
            $data?->data,
            $data?->errors,
            $data?->code ?? 200
        );
    }

    /**
     * Method JsonResponse
     *
     * @param bool $success [explicite description]
     * @param string $message [explicite description]
     * @param array|object|null $data [explicite description]
     * @param array|object|null $errors [explicite description]
     * @param int $statusCode [explicite description]
     *
     * @return JsonResponse
     */
    public function JsonResponse(
        bool $success,
        string $message = '',
        array|object|null $data = null,
        array|object|null $errors = null,
        int $statusCode = 200
    ): JsonResponse {
        return response()->json(
            [
                'success' => $success,
                'message' => $message,
                'data' => $data,
                'errors' => $errors
            ],
            $statusCode
        );
    }

    public function defaultStore()
    {
    }
}
