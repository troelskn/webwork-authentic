<?php
class UsersGateway extends pdoext_TableGateway {
  protected function validate($data, $rules = array()) {
    if (!preg_match('~[^@]+@[^@]+\.[^@]{2,}~', $data->email)) {
      $data->_errors[] = "You must enter a valid email address";
    }
    if ($data->password_repeat != null && !$data->checkPassword($data->password_repeat)) {
      $data->_errors[] = "You must enter the same value for password and password repeat";
    }
  }
  protected function validateInsert($data, $rules = array()) {
    if ($this->fetch(array('email' => $data->email))) {
      $data->_errors[] = "A user already exists with that email address";
    }
  }
}

class User extends pdoext_DatabaseRecord {
  protected $preferredCipher = 'sha1-salted';
  function setPassword($value) {
    if ($value === null) {
      $this->_data['password_salt'] = $value;
      $this->_data['encrypted_password'] = $value;
      return;
    }
    switch ($this->preferredCipher) {
    case 'crypt':
      $this->_data['password_salt'] = null;
      $this->_data['encrypted_password'] = 'crypt:' . crypt($value);
      break;
    case 'sha1-salted':
      $this->_data['password_salt'] = md5(rand());
      $this->_data['encrypted_password'] = sha1($this->password_salt . $value);
      break;
    case 'sha1-salted-tail':
      $this->_data['password_salt'] = md5(rand());
      $this->_data['encrypted_password'] = 'sha1-tail:' . sha1($value . $this->password_salt);
      break;
    case 'sha1':
      $this->_data['password_salt'] = null;
      $this->_data['encrypted_password'] = sha1($this->password_salt . $value);
      break;
    case 'md5-salted':
      $this->_data['password_salt'] = md5(rand());
      $this->_data['encrypted_password'] = 'md5:' . md5($this->password_salt . $value);
      break;
    case 'md5-salted-tail':
      $this->_data['password_salt'] = md5(rand());
      $this->_data['encrypted_password'] = 'md5-tail:' . md5($value.$this->password_salt);
      break;
    case 'md5':
      $this->_data['password_salt'] = null;
      $this->_data['encrypted_password'] = 'md5:' . md5($this->password_salt . $value);
      break;
    default:
      throw new Exception("Unknown cipher " . $this->preferredCipher);
    }
  }
  function checkPassword($value) {
    preg_match('/^(sha1|sha1-tail|md5|md5-tail|crypt):(.*)$/', $this->encrypted_password, $reg);
    if (isset($reg[1])) {
      $cipher = $reg[1];
      $encrypted_password = $reg[2];
    } else {
      // default fallback for backwards compatibility
      $cipher = 'sha1';
      $encrypted_password = $this->encrypted_password;
    }
    switch ($cipher) {
    case 'sha1':
      return $encrypted_password === sha1($this->password_salt . $value);
    case 'sha1-tail':
      return $encrypted_password === sha1($value . $this->password_salt);
    case 'md5':
      return $encrypted_password === md5($this->password_salt . $value);
    case 'md5-tail':
      return $encrypted_password === md5($value . $this->password_salt);
    case 'crypt':
      return $encrypted_password === crypt($value, $encrypted_password);
    }
    throw new Exception("Unable to check password");
  }
  function generateResetPasswordToken() {
    $this->reset_password_token = md5(mt_rand().":".microtime(true));
  }
}
