<?php
session_start();
include '../class/Route.php';
include '../class/QueryBuilder.php';
include '../class/Connect.php';
include '../class/Flash.php';

Route::start();

$db = new QueryBuilder(Connect::make());


?>
