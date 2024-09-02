<?php

use LoginPress_Social_Login\Microsoft\Auth;
use LoginPress_Social_Login\Microsoft\Handlers\Session;
use LoginPress_Social_Login\Microsoft\Models\User;

session_start();

require 'vendor/autoload.php';

$auth = new Auth(
    Session::get('tenant_id'),
    Session::get('client_id'),
    Session::get('client_secret'),
    Session::get('redirect_uri'),
    Session::get('scopes')
);
$tokens = $auth->getToken($_REQUEST['code'], $_REQUEST['state']);

$accessToken = $tokens->access_token;

$auth->setAccessToken($accessToken);

$user = new User();
echo 'Name: ' . $user->data->getDisplayName() . '<br>';
echo 'Email: ' . $user->data->getUserPrincipalName() . '<br>';
