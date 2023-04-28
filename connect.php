<?php
$conn = new PDO("mysql:host=localhost; dbname=zadmin_itonsiteservice; charset=utf8", "root", "");
//$conn = new PDO("mysql:host=localhost; dbname=zadmin_itonsiteservice; charset=utf8", "itonsiteservice", "vysa8u8e3");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>