<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}
define('__ROOT__', dirname(__FILE__));

$inputValues = array();
$inputValues['skills'] = array();
$inputValues['profile_pic'] = array();
$inputValues['resume'] = array();

$errormsg = array();

$isValidated = 0;

$states = [
    'Andaman and Nicobar Islands',
    'Andhra Pradesh',
    'Arunachal Pradesh',
    'Assam',
    'Bihar',
    'Chandigarh',
    'Chhattisgarh',
    'Dadra and Nagar Haveli and Daman and Diu',
    'Goa',
    'Gujurat',
    'Haryana',
    'Himachal Pradesh',
    'Jammu and Kashmir',
    'Jharkhand',
    'Karnataka',
    'Kerela',
    'Ladakh',
    'Lakshadweep',
    'Madhya Pradesh',
    'Maharashtra',
    'Manipur',
    'Meghalaya',
    'Mizoram',
    'Nagaland',
    'NCT of Delhi',
    'Odisha',
    'Puducherry',
    'Punjab',
    'Rajasthan',
    'Sikkim',
    'Tamil Nadu',
    'Telangana',
    'Tripura',
    'Uttarakhand',
    'Uttar Pradesh',
    'West Bengal'
];
$noOfStates = count($states);

$skills = [
    'HTML',
    'CSS',
    'JavaScript',
    'jQuery',
    'MySQL',
    'PHP'
];
$noOfSkills = count($skills);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['btn-login'])) {
    if (empty($_POST["name"])) {
        $errormsg['name'] = "Please enter your name";
    } else {
        $inputValues['name'] = htmlentities($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $inputValues['name'])) {
            $errormsg['name'] = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["email"])) {
        $errormsg['email'] = "Please enter your email";
    } else {
        $inputValues['email'] = htmlentities($_POST["email"]);
        if (!filter_var($inputValues['email'], FILTER_VALIDATE_EMAIL)) {
            $errormsg['email'] = "Invalid email address!";
        }
    }

    if (!empty($_POST["mobile"])) {
        $inputValues['mobile'] = htmlentities($_POST["mobile"]);
        if (!(is_numeric($inputValues['mobile']) and
            (strlen($inputValues['mobile']) == 10) and
            preg_match("/^[6-9]{3}[0-9]$/", $phone))) {
            $errormsg['mobile'] = "Please enter valid mobile number";
        }
    }

    if (empty($_POST["age"])) {
        $errormsg['age'] = "Please enter your age";
    } else {
        $inputValues['age'] = htmlentities($_POST["age"]);
        if ($inputValues['age'] < 20 or $inputValues['age'] > 30) {
            $errormsg['age'] = "Valid age is between 20-30";
        }
    }

    if (empty($_POST["gender"])) {
        $errormsg['gender'] = "Please select your gender";
    } else {
        $inputValues['gender'] = htmlentities($_POST["gender"]);
    }

    if (empty($_POST["state"])) {
        $errormsg['state'] = "Please select a state";
    } else {
        $inputValues['state'] = htmlentities($_POST["state"]);
    }

    if (empty($_POST["skills"])) {
        $errormsg['skills'] = "Please select at least two skills";
    } else {
        foreach ($_POST["skills"] as $skill) {
            $inputValues['skills'][] = htmlentities($skill);
        }
        if (count($_POST["skills"]) < 2) {
            $errormsg['skills'] = "Please select at least two skills";
        }
    }

    if (is_uploaded_file($_FILES['profile_pic']['tmp_name'])) {
        if (empty($_FILES['profile_pic']['name'])) {
            $errormsg['profile_pic'] = "File name is empty!";
        } else {
            if (strlen($_FILES['profile_pic']['name']) > 100) {
                $errormsg['profile_pic'] = "File name too long";
            } else {
                $formatArray = ['image/jpg', 'image/png', 'image/jpeg'];
                $fileType = mime_content_type($_FILES["profile_pic"]["tmp_name"]);
                if (!in_array($fileType, $formatArray)) {
                    $errormsg['profile_pic'] = "Photo must be in jpg or png format only";
                } else {
                    if (!($_FILES["profile_pic"]["size"] < 1001718)) {
                        $errormsg['profile_pic'] = "Photo size exceeds 1MB";
                    } else {
                        //$upload_file_name = preg_replace("/[^A-Za-z0-9 \.\-_]/", '', $_FILES['profile_pic']['name']);
                        $index = strpos($fileType, '/');
                        $extension = substr($fileType, $index + 1);
                        $upload_file_name = 'profile_photo' . time() . $_SERVER['REMOTE_ADDR'] . '.' . $extension;
                        $dest = __DIR__ . '/uploads/' . $upload_file_name;
                        if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $dest)) {
                            $errormsg['profile_pic'] = "Can't update. Try again later.";
                        } else {
                            $inputValues['profile_pic'] = $_FILES['profile_pic'];
                        }
                    }
                }
            }
        }
    } else {
        $errormsg['profile_pic'] = "Profile photo not selected";
    }

    if (is_uploaded_file($_FILES['resume']['tmp_name'])) {
        if (empty($_FILES['resume']['name'])) {
            $errormsg['resume'] = "File name is empty!";
        } else {
            if (strlen($_FILES['resume']['name']) > 225) {
                $errormsg['resume'] = "File name too long";
            } else {
                $formatArray = ['application/pdf'];
                if (!in_array($_FILES['resume']['type'], $formatArray)) {
                    $errormsg['resume'] = "Resume must be in pdf format only";
                } else {
                    if (!($_FILES["resume"]["size"] < (1001718 * 2))) {
                        $errormsg['resume'] = "File size exceeds 2MB";
                    } else {
                        //$upload_file_name = preg_replace("/[^A-Za-z0-9 \.\-_]/", '', $_FILES['resume']['name']);
                        $upload_file_name = 'resume' . time() . $_SERVER['REMOTE_ADDR'] . '.pdf';
                        $dest = __DIR__ . '/uploads/' . $upload_file_name;
                        if (!move_uploaded_file($_FILES['resume']['tmp_name'], $dest)) {
                            $errormsg['resume'] = "Can't update. Try again later.";
                        } else {
                            $inputValues['resume'] = $_FILES['resume'];
                        }
                    }
                }
            }
        }
    } else {
        $errormsg['resume'] = "Resume not selected";
    }

    if (empty($errormsg)) {
        $isValidated = 1;
    }
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
                        <form id="profile-form" enctype="multipart/form-data" class="form-signin" role="form"
                            method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="inputName">Name</label>
                                <input type="text" id="inputName" name="name" placeholder="Your name"
                                    value="<?php echo $inputValues['name']; ?>"
                                    class="form-control  <?php echo (isset($errormsg['name'])) ? 'is-invalid' : ''; ?>"
                                    required="required" autofocus="autofocus" />
                                <div class="invalid-feedback"><?php echo $errormsg['name']; ?></div>
                            </div>

                            <div class="form-group">
                                <label for="inputEmail">Email</label>
                                <input type="email" id="inputEmail" name="email" placeholder="Your email id"
                                    value="<?php echo $inputValues['email']; ?>"
                                    class="form-control <?php echo (isset($errormsg['email'])) ? 'is-invalid' : ''; ?>"
                                    required="required" />
                                <div class="invalid-feedback"><?php echo $errormsg['email']; ?></div>
                            </div>

                            <div class="form-group">
                                <label for="inputContactNumber">Contact Number (optional)</label>
                                <input type="number" id="inputContactNumber" name="mobile"
                                    placeholder="Your phone number" pattern="/[6789][0-9]{9}/" max="9999999999"
                                    value="<?php echo $inputValues['mobile']; ?>"
                                    class="form-control <?php echo (isset($errormsg['mobile'])) ? 'is-invalid' : ''; ?>" />
                                <small id="contactNumberHelpBlock" class="form-text text-muted">
                                    Enter a 10-digit phone number
                                </small>
                                <div class="invalid-feedback"><?php echo $errormsg['mobile']; ?></div>
                            </div>

                            <div class="form-group">
                                <label for="inputAge">Age</label>
                                <input type="number" min=20 max=30 id="inputAge" name="age"
                                    placeholder="How old are you?" value="<?php echo $inputValues['age']; ?>"
                                    class="form-control <?php echo (isset($errormsg['age'])) ? 'is-invalid' : ''; ?>"" required="
                                    required" />
                                <small id="ageHelpBlock" class="form-text text-muted">
                                    Age group should be in the twenties
                                </small>
                                <div class="invalid-feedback"><?php echo $errormsg['age']; ?></div>
                            </div>

                            <fieldset class="form-group">
                                <div class="row">
                                    <legend class="col-form-label col-sm-4 pt-0">Gender</legend>
                                    <div class="col-xs-8 pl-3 pl-md-0 pl-lg-0">
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect1" value="Male"
                                                <?php echo ($inputValues['gender'] == 'Male') ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect1">
                                                Male
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect2" value="Female"
                                                <?php echo ($inputValues['gender'] == 'Female') ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect2">
                                                Female
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect3" value="Genderqueer"
                                                <?php echo ($inputValues['gender'] == 'Genderqueer') ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect3">
                                                Non-Binary
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input" type="radio" name="gender"
                                                id="genderSelect4" value="Undisclosed"
                                                <?php echo ($inputValues['gender'] == 'Undisclosed') ? 'checked' : ''; ?>
                                                required="required">
                                            <label class="custom-control-label" for="genderSelect4">
                                                Prefer not to say
                                            </label>
                                        </div>
                                        <?php if (isset($errormsg['gender'])) { ?>
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
                                    <option value="<?php echo $states[$i]; ?>"
                                        <?php if ($inputValues['state'] == $states[$i]) { ?> selected="selected"
                                        <?php } ?>>
                                        <?php echo $states[$i]; ?> </option>
                                    <?php } ?>
                                </select>
                                <?php if (isset($errormsg['state'])) { ?>
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
                                            name="skills[]" value="<?php echo $skills[$i]; ?>"
                                            <?php if (in_array($skills[$i], $inputValues['skills'])) { ?>
                                            checked="checked" <?php } ?>><?php echo $skills[$i]; ?></label>
                                </div>
                                <?php }
                                    if (isset($errormsg['skills'])) { ?>
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
                                        class="custom-file-input <?php echo (isset($errormsg['profile_pic'])) ? 'is-invalid' : ''; ?>"
                                        id="inputFile01" name="profile_pic" aria-describedby="inputFileAddon01"
                                        required="required">
                                    <label class="custom-file-label" for="inputFile01">Choose image file</label>
                                </div>
                                <?php
                                    if (isset($errormsg['profile_pic'])) { ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo $errormsg['profile_pic']; ?>
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
                                        class="custom-file-input <?php echo (isset($errormsg['resume'])) ? 'is-invalid' : ''; ?>"
                                        id="inputFile02" name="resume" aria-describedby="inputFileAddon02"
                                        required="required">
                                    <label class="custom-file-label" for="inputFile02">Choose pdf file</label>
                                </div>
                                <?php
                                    if (isset($errormsg['resume'])) { ?>
                                <div class="invalid-feedback d-block">
                                    <?php echo $errormsg['resume']; ?>
                                </div>
                                <?php } else { ?>
                                <small id="resumeHelpBlock" class="form-text text-muted">
                                    Resume must be in pdf format. Max-size: 2MB
                                </small>
                                <?php } ?>
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
    <script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
    <script src="assets/js/profile.min.js"></script>
</body>

</html>