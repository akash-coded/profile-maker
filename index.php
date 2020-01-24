<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: welcome.php");
    exit;
}

$username = $password = "";
$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ("akash" == $username) {
        if ("mindfire" == $password) {
            $_SESSION["user"] = $username;
            header("Location: welcome.php");
        } else {
            $passwordErr = 1;
        }
    } else {
        $usernameErr = 1;
    }
}
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="description" content="Login page for profile maker" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="author" content="Akash Das" />
    <title>Login | Profile Maker</title>
    <link rel="icon" href="favicon.png" type="image/png" sizes="16x16" />
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/login.min.css" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Sign In</h5>
                        <form class="form-signin" role="form" method="post"
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-label-group">
                                <input type="text" id="inputUsername" class="form-control <?php echo (1 == $usernameErr) ? 'is-invalid' : '';
                                                                                            unset($usernameErr) ?>"
                                    name="username" placeholder="Username" required autofocus />
                                <label for="inputUsername">Username</label>
                                <div class="invalid-feedback">Please enter a valid username</div>
                            </div>

                            <div class="form-label-group">
                                <input type="password" id="inputPassword" class="form-control <?php echo (1 == $passwordErr) ? 'is-invalid' : '';
                                                                                                unset($passwordErr) ?>"
                                    name="password" placeholder="Password" required />
                                <label for="inputPassword">Password</label>
                                <div class="invalid-feedback">Opps! You have entered an invalid password.</div>
                            </div>

                            <button id="btnLogin" class="btn btn-lg btn-primary btn-block text-uppercase"
                                name="btn-login" type="submit">
                                Sign in
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>