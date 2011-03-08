<?php
if (request()->isPost()) {
  if (authorise_user_by_credentials(request()->body('email'), request()->body('password'))) {
    flash_message("Logged in");
    $redirect_to = request()->body('redirect_to') ?: root_url();
    response()->seeOther($redirect_to);
  }
}

if (current_user()): ?>
  <p>You are logged in as <?php e(current_user()->email); ?>. <?php echo html_link(sign_out_url(), 'Sign out'); ?></p>
<?php
 endif;

echo html_form_tag('post', sign_in_url());
if (request()->isPost()):
?>
<p class="error">Login failed</p>
<?php endif; ?>
<p>
  <label>
    Email:
    <br/>
    <?php echo html_text_field('email', request()->body('email')); ?>
  </label>
</p>
<p>
  <label>
    Password:
    <br/>
    <?php echo html_password_field('password'); ?>
  </label>
</p>
<p>
  <input type="submit" value="Sign in" />
</p>
<?php
  echo html_form_tag_end();
?>
