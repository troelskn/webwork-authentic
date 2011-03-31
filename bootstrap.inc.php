<?php
require_once 'authentic_globals.inc.php';

if (!isset($GLOBALS['AUTHENTIC']['table'])) {
  $GLOBALS['AUTHENTIC']['table'] = 'users';
}
if (!isset($GLOBALS['AUTHENTIC']['login_column'])) {
  $GLOBALS['AUTHENTIC']['login_column'] = 'email';
}

// routes
$GLOBALS['ROUTES']['~^(GET|POST)/users/sign_in~'] = "authentic/create_session";
$GLOBALS['ROUTES']['~^/users/sign_out~'] = "authentic/delete_session";

$GLOBALS['ROUTES']['~^POST/users/password~'] = "authentic/new_password_reset";
$GLOBALS['ROUTES']['~^GET/users/password/reset~'] = "authentic/new_password_reset";
$GLOBALS['ROUTES']['~^GET/users/password/fulfil~'] = "authentic/fulfil_password_reset";
$GLOBALS['ROUTES']['~^PUT/users/password~'] = "authentic/fulfil_password_reset"; // TODO -- buggy

$GLOBALS['ROUTES']['~^POST/users~'] = "authentic/create_user";
$GLOBALS['ROUTES']['~^GET/users/sign_up~'] = "authentic/create_user";
$GLOBALS['ROUTES']['~^GET/users/edit~'] = "authentic/edit_user"; // TODO
$GLOBALS['ROUTES']['~^PUT/users~'] = "authentic/edit_user"; // TODO
$GLOBALS['ROUTES']['~^DELETE/users~'] = "authentic/edit_user"; // TODO

// url helpers
function sign_in_url() {
  return root_url()."users/sign_in";
}

function sign_out_url() {
  return root_url()."users/sign_out";
}

function new_password_url($method = 'GET') {
  switch (strtoupper($method)) {
  case 'POST':
    return root_url()."users/password";
  case 'GET':
    return root_url()."users/password/reset";
  }
  throw new Exception("No URL for $method");
}

function fulfil_password_url($method = 'GET') {
  switch (strtoupper($method)) {
  case 'PUT':
    return root_url()."users/password";
  case 'GET':
    return root_url()."users/password/fulfil";
  }
  throw new Exception("No URL for $method");
}

function new_registration_url($method = 'GET') {
  switch (strtoupper($method)) {
  case 'POST':
    return root_url()."users";
  case 'GET':
    return root_url()."users/sign_up";
  }
  throw new Exception("No URL for $method");
}

function edit_registration_url($method = 'GET') {
  switch (strtoupper($method)) {
  case 'PUT':
    return root_url()."users";
  case 'GET':
    return root_url()."users/edit";
  case 'DELETE':
    return root_url()."users";
  }
  throw new Exception("No URL for $method");
}
