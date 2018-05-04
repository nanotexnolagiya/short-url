<?php 

	require('include/function.php');
	$action = (isset($_GET['action']) ? $_GET['action'] : '');

	if (isset($_POST['submit'])) {

		$login = htmlentities($_POST['login'], ENT_COMPAT, "UTF-8");
		$password = $_POST['password'];

		if(check_token("/admin/login.php", "login", "post")){

        

        $result_user = $db->query("SELECT * FROM users WHERE login = ".$db->quote($login)." AND password = '".md5($password)."'");

	        if($result_user !== false && $db->last_row_count() == 1){

	            $row = $result_user->fetch();

	            $user_token = uniqid(rand(), true);

	            $_SESSION['user']['id'] = $row['id'];

	            $_SESSION['user']['user_token'] = $user_token;

	            $_SESSION['user']['user_token_time'] = time() + (3600 * 24 * 1);

	            $result_user = $db->query("UPDATE users SET token = ".$db->quote($user_token)." WHERE id=".$row['id']);

	            header("Location: index.php");

	            exit();

	        }else

	            echo 'Bad Login';

	    }else
			echo 'Bad Token';
	}
	if($action == "logout" && isset($_SESSION['user'])){

		$db->query("UPDATE users SET token = NULL WHERE id=".$_SESSION['user']['id']);

	    unset($_SESSION['user']);



	}
 ?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>

	
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style type="text/css">
		#login-form{
			margin-top: 100px;
		}
	</style>
</head>
<body>
	<?php 

	$csrf_token = get_token("login");

	 ?>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<form id="login-form" action="login.php" method="POST">
				<span><p><b><i><input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>"></i></b></p></span>
				<div class="form-group">
					<input type="text" class="form-control" name="login" placeholder="Login" />
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" placeholder="Password" />
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-danger btn-block" name="submit" value="Login" />
				</div>
			</form>
		</div>						
	</div>
</div>
</body>
</html>