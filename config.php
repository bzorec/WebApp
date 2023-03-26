<?php
session_start();

if (isset($_SESSION['LAST_ACTIVITY']) && time() - $_SESSION['LAST_ACTIVITY'] < 1800) {
    session_regenerate_id(true);
}
$_SESSION['LAST_ACTIVITY'] = time();

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASSWORD = '';
const DB_NAME = 'vaja1';
