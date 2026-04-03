<?php
// ⚠️ احذف هذا الملف فور الانتهاء منه!
// الوصول: https://yourdomain.com/storage_setup.php

$target = __DIR__ . '/../storage/app/public';
$link   = __DIR__ . '/storage';

if (is_link($link)) {
    echo '✅ Symlink already exists at: ' . $link;
} elseif (file_exists($link)) {
    echo '⚠️ A real folder "storage" already exists. Remove it first, then re-run.';
} else {
    if (symlink($target, $link)) {
        echo '✅ SUCCESS: storage symlink created!<br>';
        echo 'Target: ' . realpath($target);
    } else {
        echo '❌ FAILED: symlink() not allowed on this server.<br>';
        echo 'Use Solution 2 (manual upload via FTP).';
    }
}
?>
