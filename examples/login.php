<?php
// load dependencies
require_once 'vendor/autoload.php';

use thom855j\PHPScrud\DB,
 thom855j\PHPAuthFramework\Auth;

// start db
DB::load('mysql', 'localhost', 'php-auth-framework', 'root', '');

// start session
session_start();

// setup class
Auth::load()->setAttribute('db',  DB::load());
Auth::load()->setAttribute('token', 'H4qRRbMkUpgvw==');

// log user in with either email og username. 
// If 3 parameter is defined true, a cookie will also be set
Auth::load()->login('demo@email.com','demo', null);

var_dump($_SESSION);
var_dump($_COOKIE);