<?php
// load file
require_once '../Validator.php';

// use namespace
use thom855j\PHPSecurity\Validator;

//var_dump(Validator::load()->_messages['required']);

if (isset($_POST['submit']))
{
    Validator::load()->check($_POST, array(
        'Username' => array(
            'required'   => true,
            'min'        => 8,
            'max'        => 20,
            'validInput' => $_POST['Username'],
            'noSpaces' =>$_POST['Username']
        )
    ));
    foreach (Validator::load()->errors() as $error)
    {
        $errors[] = $error;
    }
    var_dump($errors);
}
?>
<form action="" method="post">
    <input type="text" name="Username">
    <input type="submit" name="submit">
</form>