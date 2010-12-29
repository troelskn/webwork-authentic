<?php
require_once 'users.inc.php';

// routes
$GLOBALS['ROUTES']['~^(GET|POST)/users/sign_in$~'] = "authoriser/create_session";
$GLOBALS['ROUTES']['~^/users/sign_out$~'] = "authoriser/delete_session";

$GLOBALS['ROUTES']['~^POST/users/password$~'] = "authoriser/new_password_reset"; // TODO
$GLOBALS['ROUTES']['~^GET/users/password/reset$~'] = "authoriser/new_password_reset"; // TODO
$GLOBALS['ROUTES']['~^GET/users/password/fulfil$~'] = "authoriser/fulfil_password_reset"; // TODO
$GLOBALS['ROUTES']['~^PUT/users/password$~'] = "authoriser/fulfil_password_reset"; // TODO

$GLOBALS['ROUTES']['~^POST/users$~'] = "authoriser/create_user";
$GLOBALS['ROUTES']['~^GET/users/sign_up$~'] = "authoriser/create_user";
$GLOBALS['ROUTES']['~^GET/users/edit$~'] = "authoriser/edit_user"; // TODO
$GLOBALS['ROUTES']['~^PUT/users$~'] = "authoriser/edit_user"; // TODO
$GLOBALS['ROUTES']['~^DELETE/users$~'] = "authoriser/edit_user"; // TODO

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
