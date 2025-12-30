<?php

use App\Actions\GenerateCodeAction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use function Pest\Laravel\post;
use function Pest\Laravel\get;

beforeEach(function () {
    // Clear cache before each test
    Cache::driver('redis')->flush();
});

it('generates a short url and stores it in cache', function () {
    // Fake the code generation
    $fakeCode = 'ABC123';
    $this->mock(GenerateCodeAction::class, function ($mock) use ($fakeCode) {
        $mock->shouldReceive('execute')->andReturn($fakeCode);
    });

    $url = 'https://example.com';

    $response = post('/api/generate-url', [
        'url' => $url,
        'expires_after' => 60,
    ]);

    $response->assertStatus(200)
             ->assertJson([
                 'result' => config('app.url') . '/' . $fakeCode,
             ]);

    // Assert stored in cache
    expect(Cache::driver('redis')->get($fakeCode))->toBe($url);
});

it('redirects to the original url for a valid code', function () {
    $code = 'mycode';
    $originalUrl = 'https://laravel.com';

    // Store manually in cache
    Cache::driver('redis')->put($code, $originalUrl, 60);

    $response = get("/{$code}");

    // Expect a redirect to original URL
    $response->assertRedirect($originalUrl);
});

it('returns a 404 when redirecting a non existing code', function () {
    $response = get("/notfound");

    $response->assertStatus(404);
});


