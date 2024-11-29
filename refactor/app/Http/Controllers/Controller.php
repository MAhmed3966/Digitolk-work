<?php

namespace DTApi\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * Success response.
     *
     * @param mixed $data
     * @param int $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data,$message ="",  int $statusCode = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message

        ], $statusCode);
    }

    /**
     * Error response.
     *
     * @param string|array $message
     * @param int $statusCode
     * @param array|null $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message, int $statusCode = Response::HTTP_BAD_REQUEST, array $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Generic exception response.
     *
     * @param \Exception $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function exceptionResponse(\Exception $e)
    {
        // Log the exception
        \Log::error($e->getMessage(), ['trace' => $e->getTrace()]);

        return $this->errorResponse(
            'Something went wrong, please try again later.',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

}
