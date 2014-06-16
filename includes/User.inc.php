<?php
class User
	{
	static function login($email, $pass)
		{
		require('site/users.inc.php');
		foreach($users as $user)
			{
			if (empty($user['salt']) && $user['email'] == $email && $user['pass'] == $pass)
				{
				self::login_session($email);
				self::generate_password($email);
				header('Location: '.NAILS_ALIAS_HOME);
				}
			elseif (!empty($user['salt']) && $user['pass'] == sha1($user['salt'].$pass))
				{
				self::login_session($email);
				header('Location: '.NAILS_ALIAS_HOME);
				}
			}

		if (!self::isLogged())
			{
			header('Location: '.NAILS_ALIAS_LOGIN);
			}
		}
		
	static function login_session($email)
		{
		$_SESSION['user'] = array(
			'email'=>$email,
			'logintime'=>time()
			);
		}
	
	static function logout()
		{
		unset($_SESSION['user']);
		}
	
	static function isLogged()
		{
		return isset($_SESSION['user']) ? $_SESSION['user']['email'] : false;
		}
	
	static function generate_password($email)
		{
		require('site/users.inc.php');
		foreach($users as $i => $user)
			{
			if ($user['email'] == $email)
				{
				$users[$i]['salt'] = substr(md5(uniqid(rand(), true)), 0, NAILS_USERS_SALT_LENGTH_DEFAULT);
				$users[$i]['pass'] = sha1($users[$i]['salt'].$user['pass']);
				break;
				}
			}
		
		file_put_contents('site/users.inc.php', '<?php $users = '.var_export($users, true).';', LOCK_EX);
		}
	
	static function controls()
		{
		if ($email = self::isLogged())
			{
			return
				'<div style="position: fixed; top: 10px; left: 10px; font-size: 1.4em; padding: 1em; background: #CCC; color: black; border: solid 1px black; z-index: 9999;">Logged in as '.$email.'
					<form action="logout" method="post">
						<fieldset>
							<input type="submit" class="button" value="Logout" />
						</fieldset>
					</form>
				</div>';
			}
		else
			{
			return '';
			}
		}
	}
