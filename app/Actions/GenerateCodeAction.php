<?php

namespace App\Actions;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class GenerateCodeAction
{
    public function execute(): string
    {
        $code = Str::random(5);
        if (Cache::has($code)) {
            $this->execute();
        }

        return $code;
    }
}
