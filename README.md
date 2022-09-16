# Symfony Request Aware HTTP Client 

[![Latest Version][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-github-actions]][link-github-actions]
[![Code Coverage][ico-code-coverage]][link-code-coverage]

This library will decorate Symfony HTTP client and make it possible to retrieve full requests for debugging etc.

## Installation

### Step 1: Download

```bash
$ composer require setono/request-aware-http-client
```
## Usage

```php
<?php
use Setono\RequestAwareHttpClient\RequestAwareHttpClient;
use Setono\RequestAwareHttpClient\RequestAwareHttpClientInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class YourService
{
    private RequestAwareHttpClientInterface $httpClient;
    
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = new RequestAwareHttpClient($httpClient);
    }
    
    public function doSomething(): void
    {
        $response = $this->httpClient->request('POST', 'https://httpbin.org/post', [
            'json' => ['name' => 'John Doe']
        ]);

        $request = $this->httpClient->getRequestFromResponse($response);

        echo $request->toString();
        
        // Outputs:
        // POST https://httpbin.org/post
        // {
        //     "name": "John Doe"
        // }
    }
}
```

[ico-version]: https://poser.pugx.org/setono/request-aware-http-client/v/stable
[ico-license]: https://poser.pugx.org/setono/request-aware-http-client/license
[ico-github-actions]: https://github.com/Setono/request-aware-http-client/workflows/build/badge.svg
[ico-code-coverage]: https://codecov.io/gh/Setono/request-aware-http-client/branch/master/graph/badge.svg

[link-packagist]: https://packagist.org/packages/setono/request-aware-http-client
[link-github-actions]: https://github.com/Setono/request-aware-http-client/actions
[link-code-coverage]: https://codecov.io/gh/Setono/request-aware-http-client
