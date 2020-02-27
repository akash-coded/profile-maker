<?php
session_start();

/* Check to see if user is logged in */
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

/* Defining verbose constants */
define('ROOT', dirname(__FILE__));
define('CSRF_TOKEN', 'profile-token');
define('CSRF_TOKEN_EXPIRE', 'profile-token-expire');
define('NAME', 'name');
define('EMAIL', 'email');
define('MOBILE', 'mobile');
define('AGE', 'age');
define('GENDER', 'gender');
define('STATE', 'state');
define('SKILLS', 'skills');
define('PROFILE_PIC', 'profile_pic');
define('RESUME', 'resume');

/* Requiring necessary files */
require_once ROOT . '/includes/generate_secure_token.php';
require_once ROOT . '/includes/indian_states.php';
require_once ROOT . '/includes/programming_skills.php';

/* Variable intialization */
$inputValues = array();
$inputValues[SKILLS] = array();
$inputValues[PROFILE_PIC] = array();
$inputValues[RESUME] = array();
$errormsg = array();
$isValidated = 0;

/* Check to see if the form was submitted */
if (($_SERVER["REQUEST_METHOD"] ?? 'GET') === 'POST' && isset($_POST['btn-login'])) {
    if (($_SESSION[CSRF_TOKEN] ?? ' ') === $_POST['token']) {
        if (time() >= $_SESSION[CSRF_TOKEN_EXPIRE]) {
            echo "<script>
                        alert('You took too long. Please fill the form again.');
                        window.location.href='profile.php';
                  </script>";
        } else {
            /* Validate name field */
            if (empty($_POST[NAME])) {
                $errormsg[NAME] = "Please enter your name";
            } else {
                $inputValues[NAME] = htmlentities($_POST[NAME]);
                if (!preg_match("/^[a-zA-Z ]*$/", $inputValues[NAME])) {
                    $errormsg[NAME] = "Only letters and white space allowed";
                }
            }

            /* Validate email address field */
            if (empty($_POST[EMAIL])) {
                $errormsg[EMAIL] = "Please enter your email";
            } else {
                $inputValues[EMAIL] = htmlentities($_POST[EMAIL]);
                if (!filter_var($inputValues[EMAIL], FILTER_VALIDATE_EMAIL)) {
                    $errormsg[EMAIL] = "Invalid email address!";
                }
            }

            /* Validate mobile number field */
            if (!empty($_POST[MOBILE])) {
                $inputValues[MOBILE] = htmlentities($_POST[MOBILE]);
                if (!(preg_match("/^[6-9][0-9]{9}$/", $inputValues[MOBILE]))) {
                    $errormsg[MOBILE] = "Please enter a valid mobile number";
                }
            }

            /* Validate age field */
            if (empty($_POST[AGE])) {
                $errormsg[AGE] = "Please enter your age";
            } else {
                $inputValues[AGE] = htmlentities($_POST[AGE]);
                if ($inputValues[AGE] < 20 || $inputValues[AGE] > 30) {
                    $errormsg[AGE] = "Valid age is between 20-30";
                }
            }

            /* Validate gender selection */
            if (empty($_POST[GENDER])) {
                $errormsg[GENDER] = "Please select your gender";
            } else {
                $inputValues[GENDER] = htmlentities($_POST[GENDER]);
            }

            /* Validate state selection */
            if (empty($_POST[STATE])) {
                $errormsg[STATE] = "Please select a state";
            } else {
                $inputValues[STATE] = htmlentities($_POST[STATE]);
            }

            /* Validate skills selection */
            if (empty($_POST[SKILLS])) {
                $errormsg[SKILLS] = "Please select at least two skills";
            } else {
                foreach ($_POST[SKILLS] as $skill) {
                    $inputValues[SKILLS][] = htmlentities($skill);
                }
                if (count($_POST[SKILLS]) < 2) {
                    $errormsg[SKILLS] = "Please select at least two skills";
                }
            }

            /* Validate profile pic upload */
            if (is_uploaded_file($_FILES[PROFILE_PIC]['tmp_name'])) {
                if (empty($_FILES[PROFILE_PIC]['name'])) {
                    $errormsg[PROFILE_PIC] = "File name is empty!";
                } else {
                    if (strlen($_FILES[PROFILE_PIC]['name']) > 100) {
                        $errormsg[PROFILE_PIC] = "File name too long";
                    } else {
                        $formatArray = ['image/jpg', 'image/png', 'image/jpeg'];
                        $fileType = mime_content_type($_FILES[PROFILE_PIC]['tmp_name']);
                        if (!in_array($fileType, $formatArray)) {
                            $errormsg[PROFILE_PIC] = "Photo must be in jpg or png format only";
                        } else {
                            if (!($_FILES[PROFILE_PIC]["size"] < 1001718)) {
                                $errormsg[PROFILE_PIC] = "Photo size exceeds 1MB";
                            } else {
                                $index = strpos($fileType, '/');
                                $extension = substr($fileType, $index + 1);
                                $upload_file_name = 'profile_photo' . time() . $_SERVER['REMOTE_ADDR'] . '.' . $extension;
                                $dest = __DIR__ . '/uploads/' . $upload_file_name;
                                if (!move_uploaded_file($_FILES[PROFILE_PIC]['tmp_name'], $dest)) {
                                    $errormsg[PROFILE_PIC] = "Can't update. Try again later.";
                                } else {
                                    $inputValues[PROFILE_PIC] = $_FILES[PROFILE_PIC];
                                }
                            }
                        }
                    }
                }
            } else {
                $errormsg[PROFILE_PIC] = "Profile photo not selected";
            }

            /* Validate resume upload */
            if (is_uploaded_file($_FILES[RESUME]['tmp_name'])) {
                if (empty($_FILES[RESUME]['name'])) {
                    $errormsg[RESUME] = "File name is empty!";
                } else {
                    if (strlen($_FILES[RESUME]['name']) > 225) {
                        $errormsg[RESUME] = "File name too long";
                    } else {
                        $formatArray = ['application/pdf'];
                        if (!in_array($_FILES[RESUME]['type'], $formatArray)) {
                            $errormsg[RESUME] = "Resume must be in pdf format only";
                        } else {
                            if (!($_FILES[RESUME]["size"] < (1001718 * 2))) {
                                $errormsg[RESUME] = "File size exceeds 2MB";
                            } else {
                                $upload_file_name = RESUME . time() . $_SERVER['REMOTE_ADDR'] . '.pdf';
                                $dest = __DIR__ . '/uploads/' . $upload_file_name;
                                if (!move_uploaded_file($_FILES[RESUME]['tmp_name'], $dest)) {
                                    $errormsg[RESUME] = "Can't update. Try again later.";
                                } else {
                                    $inputValues[RESUME] = $_FILES[RESUME];
                                }
                            }
                        }
                    }
                }
            } else {
                $errormsg[RESUME] = "Resume not selected";
            }

            if (empty($errormsg)) {
                CSRFToken::clearToken();
                $isValidated = 1;
            }
        }
    } else {
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
    <meta name="description" content="User profile page" />
    <meta name="robots" content="index, follow" />
    <meta name="googlebot" content="index, follow" />
    <meta name="author" content="Akash Das" />

    <title>Your Profile | Profile Maker</title>

    <!-- Favicon -->
    <link rel="icon" href="favicon.png" type="image/png" sizes="32x32" />

    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap/css/bootstrap-tdoc.min.css" rel="stylesheet" />

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
</head>

<body id="page-top">

    <div class="container">

        <?php require_once ROOT . '/includes/navbar.php';
        ?>

        <div class="row my-5 py-5">
            <div class="col-lg-4 order-lg-1 text-center">
                <img src="//placehold.it/150" class="mx-auto img-fluid img-circle d-block" alt="avatar">
                <h6 class="mt-2">Profile Image</h6>
                <label class="custom-file">
                    <input type="file" id="file" class="custom-file-input">
                    <span class="custom-file-control">Choose file</span>
                </label>
            </div>
            <div class="col-lg-8 order-lg-2">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="" data-target="#profile" data-toggle="tab" class="nav-link active">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a href="" data-target="#edit" data-toggle="tab" class="nav-link">Edit</a>
                    </li>
                </ul>
                <div class="tab-content py-4">
                    <div class="tab-pane active" id="profile">
                        <h5 class="mb-3">User Profile</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <h6>About</h6>
                                <p>
                                    Web Designer, UI/UX Engineer
                                </p>
                                <h6>Hobbies</h6>
                                <p>
                                    Indie music, skiing and hiking. I love the great outdoors.
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h6>Recent badges</h6>
                                <a href="#" class="badge badge-dark badge-pill">html5</a>
                                <a href="#" class="badge badge-dark badge-pill">react</a>
                                <a href="#" class="badge badge-dark badge-pill">codeply</a>
                                <a href="#" class="badge badge-dark badge-pill">angularjs</a>
                                <a href="#" class="badge badge-dark badge-pill">css3</a>
                                <a href="#" class="badge badge-dark badge-pill">jquery</a>
                                <a href="#" class="badge badge-dark badge-pill">bootstrap</a>
                                <a href="#" class="badge badge-dark badge-pill">responsive-design</a>
                                <hr>
                                <span class="badge badge-primary"><em class="fa fa-user"></em> 900 Followers</span>
                                <span class="badge badge-success"><em class="fa fa-cog"></em> 43 Forks</span>
                                <span class="badge badge-danger"><em class="fa fa-eye"></em> 245 Views</span>
                            </div>
                            <div class="col-md-12">
                                <h5 class="mt-2"><span class="fa fa-clock-o ion-clock float-right"></span> Recent
                                    Activity</h5>
                            </div>
                        </div>
                        <!--/row-->
                    </div>
                    <div class="tab-pane" id="edit">
                        <form role="form">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">First name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="Jane">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Last name</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="Bishop">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Email</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="email" value="email@gmail.com">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Company</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Website</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="url" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Address</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="" placeholder="Street">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-6">
                                    <input class="form-control" type="text" value="" placeholder="City">
                                </div>
                                <div class="col-lg-3">
                                    <input class="form-control" type="text" value="" placeholder="State">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Time Zone</label>
                                <div class="col-lg-9">
                                    <select id="user_time_zone" class="form-control" size="0">
                                        <option value="Hawaii">(GMT-10:00) Hawaii</option>
                                        <option value="Alaska">(GMT-09:00) Alaska</option>
                                        <option value="Pacific Time (US &amp; Canada)">(GMT-08:00) Pacific Time (US
                                            &amp; Canada)</option>
                                        <option value="Arizona">(GMT-07:00) Arizona</option>
                                        <option value="Mountain Time (US &amp; Canada)">(GMT-07:00) Mountain Time (US
                                            &amp; Canada)</option>
                                        <option value="Central Time (US &amp; Canada)" selected="selected">(GMT-06:00)
                                            Central Time (US &amp; Canada)</option>
                                        <option value="Eastern Time (US &amp; Canada)">(GMT-05:00) Eastern Time (US
                                            &amp; Canada)</option>
                                        <option value="Indiana (East)">(GMT-05:00) Indiana (East)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Username</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="text" value="janeuser">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Password</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="password" value="11111122333">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label">Confirm password</label>
                                <div class="col-lg-9">
                                    <input class="form-control" type="password" value="11111122333">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label form-control-label"></label>
                                <div class="col-lg-9">
                                    <input type="reset" class="btn btn-secondary" value="Cancel">
                                    <input type="button" class="btn btn-primary" value="Save Changes">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once ROOT . '/includes/footer.php';
    ?>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Display uploaded filenames on file-upload fields -->
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>

    <!-- Custom scripts -->
    <script src="assets/js/profile.min.js"></script>
</body>

</html>
<!-- End of HTML document -->