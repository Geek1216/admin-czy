<?php

if (!function_exists('is_function_enabled')) {
    /**
     * @param string $name
     * @return bool
     */
    function is_function_enabled(string $name): bool {
        static $disabled = null;
        if (empty($disabled)) {
            $disabled = explode(',', ini_get('disable_functions'));
            $disabled = array_map('trim', $disabled);
        }
        return in_array($name, $disabled);
    }
}

if (!function_exists('get_range')) {
    /**
     * @param array|null $tags
     * @return \Carbon\Carbon[]
     */
    function get_range(string $period): ?array {
        if ($period === '1H') {
            $current = [
                now()->startOfHour(),
                now()->endOfHour(),
            ];
            $previous = [
                now()->startOfHour()->subHour(),
                now()->endOfHour()->subHour(),
            ];
        } else if ($period === '1D') {
            $current = [
                now()->startOfDay(),
                now()->endOfDay(),
            ];
            $previous = [
                now()->startOfDay()->subDay(),
                now()->endOfDay()->subDay(),
            ];
        } else if ($period === '1W') {
            $current = [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ];
            $previous = [
                now()->startOfWeek()->subWeek(),
                now()->endOfWeek()->subWeek(),
            ];
        } else if ($period === '1M') {
            $current = [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ];
            $previous = [
                now()->startOfMonth()->subMonth(),
                now()->endOfMonth()->subMonth(),
            ];
        } else {
            return null;
        }

        return [$current, $previous];
    }
}

if (!function_exists('str_to_bytes')) {
    /**
     * @param string|null $size
     * @return int
     */
    function str_to_bytes(?string $size): int {
        if (empty($size)) {
            return NAN;
        }
        if ($size === '-1') {
            return INF;
        }
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        return round($size);
    }
}

if (!function_exists('tagged_cache')) {
    /**
     * @param array $tags
     * @return \Illuminate\Cache\CacheManager
     */
    function tagged_cache(array $tags) {
        if (in_array(config('cache.default'), ['database', 'file'])) {
            return cache();
        } else {
            return cache()->tags($tags);
        }
    }
}
