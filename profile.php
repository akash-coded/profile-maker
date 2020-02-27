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
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="vendor/bootstrap/css/bootstrap-tdoc.min.css" rel="stylesheet" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />

    <!-- Custom fonts -->
    <link href="assets/css/login.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
</head>

<body id="page-top">

    <?php require_once ROOT . '/includes/navbar.php'; ?>

    <div class="container" style="margin-top: 60px">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-6  mx-auto">
                <div class="card card-signin my-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Your Profile</h5>
                        <?php if (1 != $isValidated) {
                        ?>
                        <form id="profile-form" enctype="multipart/form-data" class="form-signin" role="form"
                            method="post" action="<?php echo htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>">
                            <div class="form-group">
                                <label for="inputName">Name</label>
                                <input type="text" id="inputName" name="name" placeholder="Your name"
                                    value="<?php echo (isset($inputValues[NAME])) ? $inputValues[NAME] : ''; ?>"
                                    class="form-control  <?php echo (isset($errormsg[NAME])) ? 'is-invalid' : ''; ?>"
                                    required="required" autofocus="autofocus" />
                                <div class="invalid-feedback"><?php echo $errormsg[NAME]; ?></div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail" name="email" placeholder="Your email id"
                                    value="<?php echo (isset($inputValues[EMAIL])) ? $inputValues[EMAIL] : ''; ?>"
                                    class="form-control <?php echo (isset($errormsg[EMAIL])) ? 'is-invalid' : ''; ?>"
                                    required="required" />
                                <div class="invalid-feedback"><?php echo $errormsg[EMAIL]; ?></div>
                            </div>

                            <div class="form-group">
                                <label for="inputContactNumber">Contact Number (optional)</label>
                                <input type="number" id="inputContactNumber" name="mobile"
                                    placeholder="Your phone number" pattern="/[6789][0-9]{9}/" max="9999999999"
                                    value="<?php echo (isset($inputValues[MOBILE])) ? $inputValues[MOBILE] : ''; ?>"
                                    class="form-control <?php echo (isset($errormsg[MOBILE])) ? 'is-invalid' : ''; ?>" />
                                <small id="contactNumberHelpBlock" class="form-text text-muted">
                                    Enter a 10-digit phone number
                                </small>
                                <div class="invalid-feedback"><?php echo $errormsg[MOBILE]; ?></div>
                            </div>

                            <div class="form-group">
                                <label for="inputAge">Age</label>
                                <input type="number" min=20 max=30 id="inputAge" name="age"
                                    placeholder="How old are you?"
                                    value="<?php echo (isset($inputValues[AGE])) ? $inputValues[AGE] : ''; ?>"
                                    class="form-control <?php echo (isset($errormsg[AGE])) ? 'is-invalid' : ''; ?>"
                                    required="
                                    required" />
                                <small id="ageHelpBlock" class="form-text text-muted">
                                    Age group should be in the twenties
                                </small>
                                <div class="invalid-feedback"><?php echo $errormsg[AGE]; ?></div>
                            </div>

                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-4 pt-0">Gender</legend>
                                    <div class="col-xs-8 pl-3 pl-md-0 pl-lg-0">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect1" value="Male"
                                                <?php echo ((isset($inputValues[GENDER])) && ($inputValues[GENDER] == 'Male')) ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect1">
                                                Male
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect2" value="Female"
                                                <?php echo ((isset($inputValues[GENDER])) && ($inputValues[GENDER] == 'Female')) ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect2">
                                                Female
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect3" value="Genderqueer"
                                                <?php echo ((isset($inputValues[GENDER])) && ($inputValues[GENDER] == 'Genderqueer')) ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect3">
                                                Non-Binary
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect4" value="Undisclosed"
                                                <?php echo ((isset($inputValues[GENDER])) && ($inputValues[GENDER] == 'Undisclosed')) ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect4">
                                                Prefer not to say
                                            </label>
                                        </div>
                                        <?php if (isset($errormsg[GENDER])) { ?>
                                        <div class="invalid-feedback d-block">
                                            Please your gender
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </fieldset>

                            <div class="custom-control-inline form-group">
                                <label for="inputState" class="mr-5">State/UT</label>
                                <select id="inputState" name="state" class="custom-select custom-select-sm"
                                    required="required">
                                    <option value="">Select your state</option>
                                    <?php for ($i = 0; $i < $noOfStates; $i++) { ?>
                                    <option value="<?php echo INDIAN_STATES[$i]; ?>"
                                        <?php if ((isset($inputValues[STATE])) && ($inputValues[STATE] == INDIAN_STATES[$i])) { ?>
                                        selected="selected" <?php } ?>>
                                        <?php echo INDIAN_STATES[$i]; ?> </option>
                                    <?php } ?>
                                </select>
                                <?php if (isset($errormsg[STATE])) { ?>
                                <div class="invalid-feedback d-block">
                                    Please select a state
                                </div>
                                <?php } ?>
                            </div>

                            <fieldset class="form-group">
                                <legend class="col-form-label">Skills</legend>
                                <?php for ($i = 0; $i < $noOfSkills; $i++) { ?>
                                <div class="form-check-inline">
                                    <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                            name="skills[]" value="<?php echo PROGRAMMING_SKILLS[$i]; ?>"
                                            <?php if (in_array(PROGRAMMING_SKILLS[$i], $inputValues[SKILLS])) { ?>
                                            checked="checked" <?php } ?>><?php echo PROGRAMMING_SKILLS[$i]; ?></label>
                                </div>
                                <?php }
                                    if (isset($errormsg[SKILLS])) { ?>
                                <div class="invalid-feedback d-block">
                                    Please select at least 2 skills
                                </div>
                                <?php } else { ?>
                                <small id="skillsHelpBlock" class="form-text text-muted">
                                    Pick at least 2 skills
                                </small>
                                <?php } ?>
                            </fieldset>

                            <div class="form-group">
                                <label for="inputFile01" id="inputFileAddon01">Upload Profile Photo</label>
                                <div class="custom-file">
                                    <input type="file" accept="image/jpeg,image/jpg,image/png"
                                        class="custom-file-input <?php echo (isset($errormsg[PROFILE_PIC])) ? 'is-invalid' : ''; ?>"
                                        id="inputFile01" name="profile_pic" aria-describedby="inputFileAddon01"
                                        required="required">
                                    <label class="custom-file-label" for="inputFile01">Choose image file</label>
                                </div>
                                <?php if (isset($errormsg[PROFILE_PIC])) { ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo $errormsg[PROFILE_PIC]; ?>
                                </div>
                                <?php } else { ?>
                                <small id="profilePhotoHelpBlock" class="form-text text-muted">
                                    Image must be in jpg or png format. Max-size: 1MB
                                </small>
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="inputFile02" id="inputFileAddon02">Upload Resume</label>
                                <div class="custom-file">
                                    <input type="file" accept="application/pdf"
                                        class="custom-file-input <?php echo (isset($errormsg[RESUME])) ? 'is-invalid' : ''; ?>"
                                        id="inputFile02" name="resume" aria-describedby="inputFileAddon02"
                                        required="required">
                                    <label class="custom-file-label" for="inputFile02">Choose pdf file</label>
                                </div>
                                <?php if (isset($errormsg[RESUME])) { ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo $errormsg[RESUME]; ?>
                                </div>
                                <?php } else { ?>
                                <small id="resumeHelpBlock" class="form-text text-muted">
                                    Resume must be in pdf format. Max-size: 2MB
                                </small>
                                <?php } ?>
                            </div>

                            <!-- Hidden input for the implicit secure token -->
                            <input type="hidden" id="token" name="token" value="<?php echo $_SESSION[CSRF_TOKEN] ?>" />

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

    <?php require_once ROOT . '/includes/footer.php'; ?>

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