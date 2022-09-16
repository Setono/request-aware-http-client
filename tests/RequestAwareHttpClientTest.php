<?php

declare(strict_types=1);

namespace Setono\RequestAwareHttpClient;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class RequestAwareHttpClientTest extends TestCase
{
    /**
     * @test
     */
    public function it_saves_one_request(): void
    {
        $options = ['json' => ['name' => 'John Doe']];

        $httpClient = new RequestAwareHttpClient(new MockHttpClient(new MockResponse('OK')));
        $response = $httpClient->request('POST', 'https://example.com/post', $options);

        self::assertTrue($httpClient->hasRequest($response));
        $request = $httpClient->getRequest($response);

        self::assertSame($request, $httpClient->getLastRequest());
        self::assertSame('POST', $request->method);
        self::assertSame('https://example.com/post', $request->url);
        self::assertSame($options, $request->options);
        self::assertSame(<<<REQUEST
POST https://example.com/post
{
    "name": "John Doe"
}
REQUEST, $request->toString());
    }
}
