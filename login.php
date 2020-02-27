<?php
session_start();

/* Check to see if user is logged in */
if (isset($_SESSION['user'])) {
	header("Location: welcome.php");
	exit;
}

/* Defining verbose constants */
define('ROOT', dirname(__FILE__));
define('CSRF_TOKEN', 'login-token');
define('CSRF_TOKEN_EXPIRE', 'login-token-expire');

/* Requiring necessary files */
require_once ROOT . '/includes/generate_secure_token.php';
spl_autoload_register(function ($class_name) {
	require_once $_SERVER['DOCUMENT_ROOT'] . "/classes/{$class_name}" . ".php";
});

/* Variable intialization */
$username = $password = "";
$usernameErr = $passwordErr = false;

/* Check to see if the form was submitted */
if (($_SERVER["REQUEST_METHOD"] ?? 'GET') === 'POST' && isset($_POST['btn-login'])) {
	if (($_SESSION[CSRF_TOKEN] ?? ' ') === $_POST['token']) {
		if (time() >= $_SESSION[CSRF_TOKEN_EXPIRE]) {
			echo "<script>
                        alert('You took too long. Please sign in again.');
                        window.location.href='login.php';
                  </script>";
		} else {
			/* Sanitizing and Validating User Input */
			$username = htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');
			$password = htmlentities($_POST['password'], ENT_QUOTES, 'UTF-8');

			/* Database Activities */
			$userDAO = new UserModel();
			$userDAO->begin_transaction();
			$userDetails = $userDAO->getUserDetails($username, ["username", "password", "name"]);
			$userDAO->end_transaction();

			/* User Authentication */
			if ($userDetails) {
				if ($userDetails && password_verify($password, $userDetails->password)) {
					/* Picking the display name */
					$displayName = $userDetails->name ?? $userDetails->username;
					$nameParts = explode(" ", $displayName);
					$_SESSION["user"] = $nameParts[0];

					/* Unsetting the CSRF token */
					CSRFToken::clearToken();

					/* Redirect to Welcome Page */
					header("Location: welcome.php");
				} else {
					$passwordErr = true;
				}
			} else {
				$usernameErr = true;
			}
		}
	} else {
		/* Redirect to Error Page */
		header("Location: access_denied.html");
	}
} else {
	CSRFToken::setTokenWithExpiry();
}
?>

<!-- Beginning of HTML document -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="description" content="Login page for profile maker" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="author" content="Akash Das" />

    <title>Login | Profile Maker</title>

    <!-- Favicon -->
    <link rel="icon" href="favicon.png" type="image/png" sizes="32x32" />

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />

    <!-- Bootsrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap-tdoc.min.css" rel="stylesheet" />

    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="assets/css/login.min.css" />
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-shrink bg-secondary text-uppercase fixed-top" id="mainNav">
        <div class="container">
          <a class="navbar-brand js-scroll-trigger" href="#page-top">Profile Maker</a>
          <button class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <em class="fas fa-bars"></em>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-1 px-0 px-lg-2 rounded js-scroll-trigger" href="index.php#contact">Contact</a>
              </li>
              <li class="nav-item mx-0 mx-lg-1">
                <a class="nav-link py-1 px-0 px-lg-2 rounded js-scroll-trigger" href="#">
                  Sign Up
                </a>
              </li>
            </ul>
          </div>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="container my-2 pt-5">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-4">
                    <div class="card-body">

                        <!-- Sign In Form Heading -->
                        <h5 class="card-title text-center">Sign In</h5>

                        <!-- Sign In Form -->
                        <form class="form-signin" role="form" method="post"
                            action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>">

                            <!-- Username Field -->
                            <div class="form-label-group">
                                <input type="text" id="inputUsername"
                                    class="form-control <?php echo ($usernameErr) ? 'is-invalid' : ''; ?>"
                                    name="username" placeholder="Username" required="required" autofocus="autofocus" />
                                <label for="inputUsername">Username</label>
                                <div class="invalid-feedback">Please enter a valid username</div>
                            </div>

                            <!-- Password Field -->
                            <div class="form-label-group mb-1">
                                <input type="password" id="inputPassword"
                                    class="form-control <?php echo ($passwordErr) ? 'is-invalid' : ''; ?>"
                                    name="password" placeholder="Password" required="required" />
                                <label for="inputPassword">Password</label>
                                <div class="invalid-feedback">Opps! You have entered an invalid password.</div>
                            </div>

                            <!-- Reset Credentials Option -->
                            <div class="mb-2 text-right">
                                <span class="txt1">
                                    Forgot
                                </span>

                                <a href="#" class="txt2">
                                    Username/Password?
                                </a>
                            </div>

                            <!-- Hidden input for the implicit secure token -->
                            <input type="hidden" id="token" name="token" value="<?php echo $_SESSION[CSRF_TOKEN] ?>" />

                            <!-- Sign In Button -->
                            <button id="btnLogin" class="btn btn-lg btn-primary btn-block text-uppercase"
                                name="btn-login" type="submit">
                                Sign in
                            </button>
                        </form>

                        <!-- Icon Divider -->
                        <hr class="my-3">

                        <!-- New User CTA -->
                        <div class="text-center">
                            <span class="txt1 mr-2">
                                Don't have an account?
                            </span>

                            <!-- Sign Up Button -->
                            <a id="btnRegister" class="btn btn-rounded btn-success text-uppercase" href="#"
                                type="button">
                                Register
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Custom scripts -->
    <script type="text/javascript" src="assets/js/login.min.js"></script>

</body>

</html>
<!-- End of HTML document -->