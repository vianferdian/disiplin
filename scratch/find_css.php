<?php
function findFiles($dir, $pattern) {
    $results = [];
    $files = @scandir($dir);
    if (!$files) return $results;
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path) && !str_contains($path, 'vendor') && !str_contains($path, 'node_modules')) {
            $results = array_merge($results, findFiles($path, $pattern));
        } elseif (is_file($path) && str_contains(strtolower($file), $pattern)) {
            $results[] = $path . ' (' . filesize($path) . ' bytes)';
        }
    }
    return $results;
}

print_r(findFiles(__DIR__ . '/..', 'style.css'));
