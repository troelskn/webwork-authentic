<?php
if (request()->isPost()) {
  $user = authentic_users()->load(request()->body('user'));
  if ($user_id = authentic_users()->insert($user)) {
    flash_message("Account created");
    postman()->deliver('signed_up', array('user_id' => $user_id));
    response()->seeOther(root_url());
  }
} else {
  $user = authentic_users()->create();
}

echo html_form_tag('post', users_url());
foreach ($user->_errors as $error): ?>
<p class="error"><?php e($error); ?></p>
<?php endforeach; ?>
<p>
  <label>
    Email:
    <br/>
    <?php echo html_text_field('user[email]', $user->email); ?>
  </label>
</p>
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
  <input type="submit" value="Sign me up" />
</p>
<?php
  echo html_form_tag_end();
?>
