<?php
require_valid_user();
$user = current_user();
if (request()->isPut()) {
  $fields = request()->body('user');
  $user->email = $fields['email'];
  if ($fields['password']) {
    $user->password = $fields['password'];
    $user->password_repeat = $fields['password_repeat'];
  }
  if (db()->users->update($user)) {
    flash_message("Profile updated");
    response()->seeOther(root_url());
  }
}

echo html_form_tag('put', edit_registration_url('put'));
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
  <input type="submit" value="Save changes" />
</p>
<?php
  echo html_form_tag_end();
?>
