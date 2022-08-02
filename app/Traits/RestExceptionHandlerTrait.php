<?php
declare(strict_types=1);

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Trait RestExceptionHandlerTrait
 * @package App\Traits
 */
trait RestExceptionHandlerTrait
{
    /**
     * Create a new JSON response based on exception type.
     *
     * @param Request $request
     * @param \Exception $e
     * @return JsonResponse
     */
    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        if ($e instanceof HttpException) {
            if ($e->getStatusCode() == 401) {
                return $this->unauthorized();
            } elseif ($e->getStatusCode() == 404) {
                return $this->notFound();
            } elseif ($e->getStatusCode() < 500) {
                return $this->badRequest();
            } else {
                return $this->internalError();
            }
        } else {
            return $this->internalError();
        }
    }

    /**
     * Return JSON response for bad request.
     *
     * @param string $message
     * @param int    $statusCode
     * @return JsonResponse
     */
    protected function badRequest($message = 'Bad request', $statusCode = 400)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Return JSON response for unauthorized.
     *
     * @param string $message
     * @param int    $statusCode
     * @return JsonResponse
     */
    protected function unauthorized($message = 'Unauthorized', $statusCode = 401)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Return JSON response for not found.
     *
     * @param string $message
     * @param int    $statusCode
     * @return JsonResponse
     */
    protected function notFound($message = 'Not found', $statusCode = 404)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Return JSON response for internal error.
     *
     * @param string $message
     * @param int    $statusCode
     * @return JsonResponse
     */
    protected function internalError($message = 'Internal error', $statusCode = 500)
    {
        return $this->jsonResponse(['error' => $message], $statusCode);
    }

    /**
     * Return JSON response.
     *
     * @param array|null $payload
     * @param int        $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse(array $payload = null, $statusCode = 404)
    {
        $payload = $payload ?: [];
        return response()->json($payload, $statusCode);
    }
}
