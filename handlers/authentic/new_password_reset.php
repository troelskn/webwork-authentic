<?php
if (request()->isPost()) {
  $user = authentic_users()->fetch(array('email' => request()->body('user', 'email')));
  if ($user) {
    $user->generateResetPasswordToken();
    authentic_users()->saveOrFail($user);
    postman()->deliver(
      'reset_password',
      array(
        'user_id' => $user->id,
        'url' => url(fulfil_password_url(), array('token' => $user->resetPasswordToken))));
  }
  flash_message("Instructions have been sent to your email address");
  response()->seeOther(sign_in_url());
}

echo html_form_tag('post', passwords_url());
?>
<p>
  Enter your email address to begin the recovery of your account.
</p>
<p>
  <label>
    Email:
    <br/>
    <?php echo html_text_field('user[email]', ''); ?>
  </label>
</p>
<p>
  <input type="submit" value="Reset my password" />
</p>
<?php
  echo html_form_tag_end();
?>
