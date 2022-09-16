<?php

declare(strict_types=1);

namespace Setono\RequestAwareHttpClient\Request;

use Symfony\Contracts\HttpClient\ResponseInterface;

final class Request
{
    /** @readonly */
    public string $method;

    /** @readonly */
    public string $url;

    /** @readonly */
    public array $options;

    public ?ResponseInterface $response = null;

    public function __construct(string $method, string $url, array $options)
    {
        $this->method = $method;
        $this->url = $url;
        $this->options = $options;
    }

    public function toString(): string
    {
        /** @var mixed $body */
        $body = $this->options['body'] ?? '';
        if (!is_string($body) && !is_array($body)) {
            $body = sprintf('Body is of type %s and could not be stringified...', gettype($body));
        }

        if (is_array($body)) {
            $body = print_r($body, true);
        }

        if (isset($this->options['json'])) {
            try {
                $body = json_encode(
                    $this->options['json'],
                    \JSON_THROW_ON_ERROR | \JSON_HEX_TAG | \JSON_HEX_APOS | \JSON_HEX_AMP | \JSON_HEX_QUOT | \JSON_PRESERVE_ZERO_FRACTION | \JSON_PRETTY_PRINT
                );
            } catch (\JsonException $e) {
                $body = sprintf('An error happened when trying to encode the JSON body: %s', $e->getMessage());
            }
        }

        return sprintf("%s %s\n%s", $this->method, $this->url, $body);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
