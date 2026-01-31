<?php
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/..', RecursiveDirectoryIterator::SKIP_DOTS));
foreach ($it as $f) {
    if ($f->isFile() && preg_match('/\.php$/i', $f->getFilename())) {
        $h = fopen($f->getPathname(), 'rb');
        $b = fread($h, 3);
        fclose($h);
        if ($b === "\xEF\xBB\xBF") {
            echo $f->getPathname() . PHP_EOL;
        }
    }
}
