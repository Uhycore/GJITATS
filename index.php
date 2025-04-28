<?php
echo "Hello, World! This is a test file.\n";

require_once 'model/perorangan.php';

$test = new ModelPerorangan();

echo "<pre>";
print_r($test->getAllPerorangan());
echo "</pre>";


echo " coba";
