<?php
echo "Hello, World! This is a test file.";

require_once 'model/perorangan.php';

$test = new ModelPerorangan();

echo "<pre>";
print_r($test->getAllPerorangan());
echo "</pre>";


$test->deletePeroranganByUserId(1745550962);

echo "<pre>";
print_r($test->getAllPerorangan());
echo "</pre>";