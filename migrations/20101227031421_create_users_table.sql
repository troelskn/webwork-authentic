CREATE TABLE users (
  id SERIAL,
  email varchar(255) NOT NULL,
  encrypted_password varchar(128) NOT NULL,
  password_salt varchar(255) NOT NULL,
  reset_password_token varchar(255) DEFAULT NULL,
  UNIQUE KEY index_users_on_email (email),
  UNIQUE KEY index_users_on_reset_password_token (reset_password_token)
);
