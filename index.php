<?php
echo "Hello, World! This is a test file.";

include 'model/role.php';

$test = new ModelRole();
$roles = $test->getAllRoles();
echo "<pre>";
print_r($roles);
echo "</pre>";
