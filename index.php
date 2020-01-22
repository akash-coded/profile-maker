<?php
session_start();

/* if (isset($_SESSION["user"])!="") {
header("Location: welcome.php");
exit;
} */

$username = $password = "";
$usernameErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username == "akash") {
        if ($password == "mindfire") {
            $_SESSION["user"] = $username;
            header("Location:welcome.php");
        } else {
            $passwordErr = "Wrong password";
        }
    } else {
        $usernameErr = "Invalid username";
    }
}
?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="description" content="A simple resume created using HTML and Bootstrap" />
    <meta name="robots" content="index, follow" />
    <meta name='author' content="Akash Das">
    <title>Login</title>
    <link rel="icon" href="favicon.png" type="image/png" sizes="16x16" />
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="assets/css/login_style.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Sign in</h3>
                    </div>
                    <div class="panel-body">
                        <form accept-charset="UTF-8" role="form" method="post"
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <fieldset>
                                <div class="form-group">
                                    <input id="login-username" class="form-control"
                                        placeholder="<?php echo !$usernameErr ? "Username" : $usernameErr ?>"
                                        name="username" type="text">
                                </div>
                                <div class="form-group">
                                    <input id="login-password" class="form-control"
                                        placeholder="<?php echo !$passwordErr ? "Password" : $passwordErr ?>"
                                        name="password" type="password" value="">
                                </div>
                                <input id="btn-login" class="btn btn-lg btn-primary btn-square" name="btn-login"
                                    type="submit" value="Login">
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>