<?php
require_once 'config/config.php';


function getMySQL(){
    return new PDO("mysql:host=".DB_HOST.";dbname=".DB_DATABASE, DB_USERNAME, DB_PASSWORD);
}

function getSWMySQL(){
    return new PDO("mysql:host=".SW_DB_HOST.";dbname=".SW_DB_DATABASE, SW_DB_USERNAME, SW_DB_PASSWORD);
}

function getBWMySQL(){
    return new PDO("mysql:host=".BW_DB_HOST.";dbname=".BW_DB_DATABASE, BW_DB_USERNAME, BW_DB_PASSWORD);
}