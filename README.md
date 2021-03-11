# laravel-creates-http-requests

Trait for creating http Request objects in test cases, works in conjunction with
 Laravel's MakesHttpRequests trait.

Useful for directly testing a Laravel middleware handler.

Here's a contrived example of how to use it.
```php

use Tests\TestCase;
use WmJasonWard\Laravel\Testing\CreatesHttpRequests;

class MyMiddlewareHandlerTest extends TestCase
{
    public function test_my_middleware_handler ()
    {
        $middleware = new MyMiddleware();

        $request = $this->createGetRequest('/', [
            'Authorization' => 'Basic dJdpbGlvOnBhc3N3c3Jk',
            ]);

        $r = $middleware->handle($request, function($response) {
            return null;
        });

        $this->assertNull($r);
    }
}
```

Using the Request class directly may suffice as well:
```php
 use Illuminate\Http\Request;

 $request = Request::create('/');
```