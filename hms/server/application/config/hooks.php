<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|$hook['post_controller_constructor'][] = array(
    'class' => 'LanguageLoader',
    'function' => 'initialize',
    'filename' => 'LanguageLoader.php',
    'filepath' => 'hooks'
);
$hook['post_controller_constructor'][] = array(
    'class' => 'ModuleLoader',
    'function' => 'initialize',
    'filename' => 'ModuleLoader.php',
    'filepath' => 'hooks'
);
*/
$hook['post_controller_constructor'][] = array(
    'class' => 'RestAuthenticator',
    'function' => 'requestChecker',
    'filename' => 'RestAuthenticator.php',
    'filepath' => 'hooks'
);
