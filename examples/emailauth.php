<?php

// load dependencies
require_once '../vendor/autoload.php';

use thom855j\PHPScrud\DB,
    thom855j\PHPAuthFramework\Auth,
    thom855j\PHPAuthFramework\Email;

// start db
DB::load('mysql', 'localhost', 'php-auth-framework', 'root', '');

// start session
session_start();

// setup class
Email::load(DB::load());
Auth::load(DB::load());
// to activate an account, a token is generated in db
Email::load()->from     = 'admin@email.com';
Email::load()->to       = 'user@email.com';
Email::load()->subject  = 'Validate Account';
Email::load()->template = '../templates/auth_email.php';
Email::load()->data     = array(
    'firstname' => 'Thomas',
    'username'  => 'demo',
    'password'  => '123',
    'token'     => Auth::load()->authToken(1)
);


Email::load()->send();

