<?php

$mysqli = new mysqli('localhost','kendallpomerleau','term.me.sql.2018','news');

if($mysqli->connect_errno) {
    printf("Connection Failed: %s\n", $mysqli->connect_error);
    exit;
}
?>