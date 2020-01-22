<?php
session_start();
?>
<html>
<?php
if (isset($_SESSION["user"])) {
    echo "Welcome! " . $_SESSION["user"];
    ?>
<br />
<a href="profile.php">Profile</a>
<a href="index.php">LOG OUT</a>
<?php
} else {
    header("Location: index.php");
}
?>

</html>