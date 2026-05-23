<?php

if (! function_exists('setting')) {
    function setting(string $key, ?string $default = null): ?string
    {
        return \App\Models\Setting::get($key, $default);
    }
}
