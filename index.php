<?php
echo "Hello, World! This is a test file.";

include 'model/perorangan.php';

$test = new ModelProrangan();

echo "<pre>";
print_r($test->getAllProrangan());
echo "</pre>";