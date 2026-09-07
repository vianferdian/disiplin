<?php
$origPath = __DIR__ . '/../../assets/css/style.css';
echo "Orig exists: " . (file_exists($origPath) ? 'YES' : 'NO') . "\n";
if (file_exists($origPath)) {
    echo "Orig size: " . filesize($origPath) . " bytes\n";
}
