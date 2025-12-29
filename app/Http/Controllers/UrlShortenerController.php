<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Http\Requests\GenerateUrlRequest;
use App\Actions\GenerateCodeAction;

class UrlShortenerController extends Controller
{
    public function generateUrl(GenerateUrlRequest $request, GenerateCodeAction $generateCodeAction)
    {
        $code = $generateCodeAction->execute();
        $shortUrl = Cache::driver('redis')->put($code, $request->url, $request->expires_after);

        $result = config('app.url') . '/' . $code;
        return compact('result');
    }

    public function redirectToUrl(string $code)
    {
        $result = Cache::driver('redis')->get($code);
        abort_if(!$result, 404);

        return redirect($result);
    }
}
