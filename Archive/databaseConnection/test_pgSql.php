<?php
echo "pgsql: ";
var_dump(function_exists('pg_connect'));
echo "pdo_pgsql: ";
var_dump(extension_loaded('pdo_pgsql'));
?>
