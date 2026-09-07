<?php
$css = file_get_contents(__DIR__ . '/../public/assets/css/style.css');

preg_match_all('/\.widget-[a-zA-Z0-9_-]+/', $css, $m1);
echo "Widget classes: " . implode(', ', array_slice(array_unique($m1[0]), 0, 30)) . "\n";

preg_match_all('/\.card-[a-zA-Z0-9_-]+/', $css, $m2);
echo "Card classes: " . implode(', ', array_slice(array_unique($m2[0]), 0, 30)) . "\n";

preg_match_all('/\.media-[a-zA-Z0-9_-]+/', $css, $m3);
echo "Media classes: " . implode(', ', array_slice(array_unique($m3[0]), 0, 30)) . "\n";
