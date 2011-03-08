<?php
session()->set('current_user_id', null);
$GLOBALS['current_user'] = null;
flash_message("Logged out");
$redirect_to = request()->body('redirect_to') ?: root_url();
response()->seeOther($redirect_to);
