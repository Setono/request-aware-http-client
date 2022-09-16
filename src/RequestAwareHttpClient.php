<?php

declare(strict_types=1);

namespace Setono\RequestAwareHttpClient;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Setono\RequestAwareHttpClient\Request\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;
use Symfony\Contracts\Service\ResetInterface;

final class RequestAwareHttpClient implements RequestAwareHttpClientInterface, ResetInterface, LoggerAwareInterface
{
    private HttpClientInterface $decorated;

    /** @var list<Request> */
    private array $requests = [];

    public function __construct(HttpClientInterface $client)
    {
        $this->decorated = $client;
    }

    public function request(string $method, string $url, array $options = []): ResponseInterface
    {
        $request = new Request($method, $url, $options);
        $this->requests[] = $request;

        $response = $this->decorated->request($method, $url, $options);

        $request->response = $response;

        return $response;
    }

    public function stream($responses, float $timeout = null): ResponseStreamInterface
    {
        return $this->decorated->stream($responses, $timeout);
    }

    public function getRequests(): array
    {
        return $this->requests;
    }

    public function hasRequest(ResponseInterface $response): bool
    {
        foreach ($this->requests as $request) {
            if (null !== $request->response && $request->response === $response) {
                return true;
            }
        }

        return false;
    }

    public function getRequest(ResponseInterface $response): Request
    {
        foreach ($this->requests as $request) {
            if (null !== $request->response && $request->response === $response) {
                return $request;
            }
        }

        throw new \InvalidArgumentException('No request matches the given response');
    }

    public function getLastRequest(): ?Request
    {
        $c = count($this->requests);

        return $c > 0 ? $this->requests[$c - 1] : null;
    }

    public function reset(): void
    {
        if ($this->decorated instanceof ResetInterface) {
            $this->decorated->reset();
        }

        $this->requests = [];
    }

    public function setLogger(LoggerInterface $logger): void
    {
        if ($this->decorated instanceof LoggerAwareInterface) {
            $this->decorated->setLogger($logger);
        }
    }
}
