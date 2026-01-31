<?php
$files = [__DIR__ . '/../routes/web.php'];
foreach ($files as $file) {
    $contents = file_get_contents($file);
    if (substr($contents, 0, 3) === "\xEF\xBB\xBF") {
        file_put_contents($file, substr($contents, 3));
        echo "Removed BOM from: $file\n";
    } else {
        echo "No BOM in: $file\n";
    }
}
