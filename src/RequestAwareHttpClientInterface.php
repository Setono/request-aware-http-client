<?php

declare(strict_types=1);

namespace Setono\RequestAwareHttpClient;

use Setono\RequestAwareHttpClient\Request\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface RequestAwareHttpClientInterface extends HttpClientInterface
{
    /**
     * @return list<Request>
     */
    public function getRequests(): array;

    /**
     * Returns true if a request that matches the response exists
     */
    public function hasRequest(ResponseInterface $response): bool;

    /**
     * Returns the request associated with the given response
     *
     * @throws \InvalidArgumentException if no request matches the response
     */
    public function getRequest(ResponseInterface $response): Request;

    /**
     * Returns the last request if any requests were made, else it returns null
     */
    public function getLastRequest(): ?Request;
}
