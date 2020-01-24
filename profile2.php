<?php
session_start();
// if (!isset($_SESSION["user"])) {
//     header("Location: index.php");
//     exit;
// }
define('__ROOT__', dirname(__FILE__));

$inputValues = array();
$errormsg = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn-login'])) {
}
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
    <meta name="description" content="User profile page" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="author" content="Akash Das" />
    <title>Your Profile | Profile Maker</title>
    <link rel="icon" href="favicon.png" type="image/png" sizes="16x16" />
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="vendor/bootstrap/css/bootstrap-tdoc.min.css" rel="stylesheet" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="assets/css/login.min.css" />
</head>

<body id="page-top">
    <?php
    require_once(__ROOT__ . '/includes/navbar.php');
    ?>
    <div class="container" style="margin-top: 60px">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-6  mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Your Profile</h5>
                        <?php if (1 != $isValidated) { ?>
                        <form id="profile-form" class="form-signin" role="form" method="post"
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="inputName">Name</label>
                                <input type="text" id="inputName" name="name" placeholder="Your name"
                                    value="<?php echo $inputValues['name']; ?>"
                                    class="form-control  <?php echo (isset($errormsg['name'])) ? 'is-invalid' : ''; ?>"
                                    required autofocus />
                                <div class="invalid-feedback">Only letters and a space allowed</div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail" name="email" placeholder="Your email id"
                                    value="<?php echo $inputValues['email']; ?>"
                                    class="form-control <?php echo (isset($errormsg['email'])) ? 'is-invalid' : ''; ?>"
                                    required />
                                <div class="invalid-feedback">Not a valid email id</div>
                            </div>

                            <div class="form-group">
                                <label for="inputMobile">Contact Number (optional)</label>
                                <input type="number" id="inputMobile" name="mobile" placeholder="Your phone number"
                                    value="<?php echo $inputValues['mobile']; ?>"
                                    class="form-control <?php echo (isset($errormsg['mobile'])) ? 'is-invalid' : ''; ?>" />
                                <div class="invalid-feedback">Not a valid phone number</div>
                            </div>

                            <div class="form-group">
                                <label for="inputAge">Age</label>
                                <input type="number" id="inputAge" name="mobile" placeholder="How old are you?"
                                    value="<?php echo $inputValues['age']; ?>"
                                    class="form-control <?php echo (isset($errormsg['age'])) ? 'is-invalid' : ''; ?>"
                                    required />
                                <div class="invalid-feedback">Not a valid phone number</div>
                            </div>

                            <button id="btnLogin" class="btn btn-primary text-uppercase" name="btn-login" type="submit">
                                Submit
                            </button>
                        </form>
                        <?php } else { ?>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    require_once(__ROOT__ . '/includes/footer.php');
    ?>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/profile.min.js"></script>
</body>

</html>