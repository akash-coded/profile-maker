<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

define('__ROOT__', dirname(__FILE__));
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
    <style>
    .error {
        color: #FF0000;
    }
    </style>
</head>

<body>
    <?php
    require_once(__ROOT__ . '/includes/navbar.php');
    $nameErr = $emailErr = $genderErr = $stateErr = $skillErr = $mobErr = $ageErr = $profilePhotoErr = $resumeErr = "";
    $name = $email = $gender = $state = $mob = $age = $profilePhoto = $resume = "";
    $skill = [];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["name"])) {
            $nameErr = "Please enter your name";
        } else {
            $name = test_input($_POST["name"]);
            if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
                $nameErr = "Only letters and white space allowed";
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Please enter your email";
        } else {
            $email = test_input($_POST["email"]);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Please enter valid email";
            }
        }
        if (empty($_POST["mob"])) {
            $mobErr = "Please enter your mobile";
        } else {
            $mob = test_input($_POST["mob"]);
            if ((is_numeric($mob) and strlen($mob) == 10)) {
            } else {
                $mobErr = "Please enter valid mobile number";
            }
        }
        if (empty($_POST["age"])) {
            $ageErr = "Please enter your age";
        } else {
            $age = test_input($_POST["age"]);
            if ($age < 20 or $age > 30) {
                $ageErr = "Valid age is between 20-30";
            }
        }
        if (empty($_POST["gender"])) {
            $genderErr = "Please select a gender";
        } else {
            $gender = test_input($_POST["gender"]);
        }
        if (empty($_POST["state"])) {
            $stateErr = "Please select a state";
        } else {
            $state = test_input($_POST["state"]);
        }
        if (count($_POST["skill"]) < 2) {
            $skillErr = "Please select at least two skill";
        } else {
            foreach ($_POST['skill'] as $skills) {
                $skill[] = test_input($skills);
            }
        }

        $photoName = $_FILES["profilePhoto"]["tmp_name"];
        $photoType = mime_content_type($photoName);
        $formatArray = ['jpg', 'png', 'jpeg'];
        $index = strpos($photoType, '/');
        $photoFormat = substr($photoType, $index + 1);
        if (!(in_array($photoFormat, $formatArray) and $_FILES["profilePhoto"]["size"] < 1001718)) {
            $profilePhotoErr = "Photo size or format is not supported.";
        }

        $resumeName = $_FILES["resume"]["tmp_name"];
        $resumeType = mime_content_type($resumeName);
        $index = strpos($resumeType, '/');
        $resumeFormat = substr($resumeType, $index + 1);
        if (!($resumeFormat == 'pdf' and $_FILES["resume"]["size"] < (1001718 * 2))) {
            $resumeErr = "Resume size or format is not supported.";
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
    <p><span class="error">* required field</span></p>
    <form method="post" style="margin-top: 140px" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
        enctype='multipart/form-data'>
        Name: <input type="text" name="name" value="<?php echo $name; ?>">
        <span class="error">* <?php echo $nameErr; ?></span>
        <br /><br />
        E-mail: <input type="email" name="email" value="<?php echo $email; ?>">
        <span class="error">* <?php echo $emailErr; ?></span>
        <br /><br />
        Mobile no:<input type="text" name="mob" value="<?php echo $mob; ?>" pattern="[1-9]{1}[0-9]{9}" maxlength="10">
        <span class="error">* <?php echo $mobErr; ?></span>
        <br /><br />
        Age:<input type="text" name="age" value="<?php echo $mob; ?>">
        <span class="error">* <?php echo $ageErr; ?></span>
        <br /><br />
        Gender:
        <input type="radio" name="gender" <?php if (isset($gender) && $gender == "female") {
                                                echo "checked";
                                            }
                                            ?> value="female">Female
        <input type="radio" name="gender" <?php if (isset($gender) && $gender == "male") {
                                                echo "checked";
                                            }
                                            ?> value="male">Male
        <input type="radio" name="gender" <?php if (isset($gender) && $gender == "other") {
                                                echo "checked";
                                            }
                                            ?> value="other">Other
        <span class="error">* <?php echo $genderErr; ?></span>
        <br /><br />

        State:
        <select name="state">
            <option value="">Select any state</option>
            <option value="AndhraPradesh">AndhraPradesh</option>
            <option value="Odisha">Odisha</option>
            <option value="Gujurat">Gujurat</option>
            <option value="MadhyaPradesh">MadhyaPradesh</option>
        </select>
        <span class="error">* <?php echo $stateErr; ?></span>
        <br /><br />
        Skills:
        <input type='checkbox' name='skill[]' value='c'>C
        <input type='checkbox' name='skill[]' value='java'>java
        <input type='checkbox' name='skill[]' value='python'>python
        <input type='checkbox' name='skill[]' value='web development'>web development
        <span class="error">* <?php echo $skillErr; ?></span>
        <br /><br />
        Select your profile photo:
        <input type='file' name='profilePhoto'><span class="error">* <?php echo $profilePhotoErr; ?></span>
        <br /><br />
        Select your resume:
        <input type='file' name='resume'><span class="error">* <?php echo $resumeErr; ?></span>
        <br /><br />
        <input type="submit" name="submit" value="Submit">
    </form>
    <div>
        <?php
        if ($_POST['submit'] != "") {

            if ($nameErr == "") {
                echo "<p>Name: " . $name . "</p>";
            }
            if ($emailErr == "") {
                echo "<p>Email: " . $email . "</p>";
            }
            if ($mobErr == "") {
                echo "<p>Mobile no: " . $mob . "</p>";
            }
            if ($ageErr == "") {
                echo "<p>Age: " . $age . "</p>";
            }
            if ($genderErr == "") {
                echo "<p>Gender: " . $gender . "</p>";
            }
            if ($stateErr == "") {
                echo "<p>State: " . $state . "</p>";
            }
            if ($skillErr == "") {
                echo "<p>Skills:" . implode(', ', $skill) . "</p>";
            }
            if ($profilePhotoErr == '') {
                foreach ($_FILES["profilePhoto"] as $k => $v) {
                    echo $k . "=>" . $v . '<br />';
                }
            }
            if ($resumeErr == '') {
                foreach ($_FILES["resume"] as $k => $v) {
                    echo $k . "=>" . $v . '<br />';
                }
            }
        }
        ?>
    </div>
    <?php
    require_once(__ROOT__ . '/includes/footer.php');
    ?>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/profile.min.js"></script>
</body>

</html>