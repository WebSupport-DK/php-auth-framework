<?php
// load dependencies
require_once '../vendor/autoload.php';

use thom855j\PHPScrud\DB,
 thom855j\PHPAuthFramework\Auth,
 thom855j\PHPEmail\Email;

// start db
DB::load('mysql', 'localhost', 'php-auth-framework', 'root', '');

// start session
session_start();

// setup class
Email::load(DB::load());
Auth::load(DB::load());
// to reset an account, a token is generated in db
Email::load()->from = 'admin@email.com';
Email::load()->to = 'user@email.com';
Email::load()->subject = 'Validate Account';
Email::load()->template = '../templates/reset_email.php';
Email::load()->data = array(
    'firstname' => 'Thomas',
    'username' => 'demo',
    'password' => '123',
    'token' => Auth::load()->resetToken(1)
    );

    var_dump(Email::load()->data);
Email::load()->send();

