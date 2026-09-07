<?php
$css = file_get_contents(__DIR__ . '/../../public/assets/css/style.css');
echo "File length: " . strlen($css) . "\n";
echo "First 500 chars:\n" . substr($css, 0, 500) . "\n";

// Search for any imports
preg_match_all('/@import [^;]+;/', $css, $imports);
print_r($imports[0]);
