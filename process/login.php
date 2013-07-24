<?php
if (isset($_POST['email']) && isset($_POST['pass']))
	{
	User::login(Sanitise::reverse_magic_quotes($_POST['email']), Sanitise::reverse_magic_quotes($_POST['pass']));
	}

