<?php

namespace App\Exceptions;

use Exception;

class OpenAIException extends Exception
{
    protected array $context = [];

    public function __construct(string $message = "", int $code = 0, ?\Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    /**
     * Get the exception context
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Report the exception
     *
     * @return bool
     */
    public function report(): bool
    {
        return false;
    }

    /**
     * Render the exception as an HTTP response
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue lors de la communication avec le service IA.',
            'error' => config('app.debug') ? $this->getMessage() : null,
        ], 503);
    }
}
