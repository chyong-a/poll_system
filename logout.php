<?php
require_once('require.php');

session_start();
$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$auth->logout();
redirect('index.php');