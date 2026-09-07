<?php
$css = file_get_contents(__DIR__ . '/../public/assets/css/style.css');

preg_match_all('/\.widget-stat[^{]*\{[^}]*\}/', $css, $matches);
foreach ($matches[0] as $rule) {
    echo $rule . "\n-------------------\n";
}
