<?php
if (current_user()) {
  response()->seeOther(root_url());
}
$user = request()->query('token') ? authentic_users()->fetch(array('reset_password_token' => request()->query('token'))) : null;
if (!$user) {
  throw new http_Gone();
}
if (request()->isPut()) {
  $user->password = request()->body('user', 'password');
  $user->passwordRepeat = request()->body('user', 'password_repeat');
  $user->resetPasswordToken = null;
  if (authentic_users()->save($user)) {
    if (!authorise_user_by_credentials($user->email, request()->body('user', 'password'))) {
      throw new Exception("Something is aloof");
    }
    flash_message("Your password has been changed");
    response()->seeOther(root_url());
  }
}

echo "<p>Enter your new password to complete the recovery process.</p>";
echo html_form_tag('put', url(passwords_url(), request()->query()));
foreach ($user->_errors as $error): ?>
<p class="error"><?php e($error); ?></p>
<?php endforeach; ?>
<p>
  <label>
    Password:
    <br/>
    <?php echo html_password_field('user[password]'); ?>
  </label>
</p>
<p>
  <label>
    Repeat Password:
    <br/>
    <?php echo html_password_field('user[password_repeat]'); ?>
  </label>
</p>
<p>
  <input type="submit" value="Change password" />
</p>
<?php
  echo html_form_tag_end();
?>
