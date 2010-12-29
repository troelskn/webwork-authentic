<?php
session()->set('current_user_id', null);
$GLOBALS['current_user'] = null;
flash_message("Logged out");
response()->seeOther(request()->body('redirect_to', root_url()));
