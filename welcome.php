<?php
session_start();

/* Check to see if user is logged in */
if (!isset($_SESSION['user'])) {
	header("Location: login.php");
	exit;
}

/* Defining verbose constants */
define('ROOT', dirname(__FILE__));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Welcome page upon successfully logging in" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Akash Das" />

    <title>Welcome | Profile Maker</title>

    <!-- Favicon -->
    <link rel="icon" href="favicon.png" type="image/png" sizes="32x32" />

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />

    <!-- Bootstrap CSS -->
    <link href="vendor/bootstrap/css/bootstrap-tdoc.min.css" rel="stylesheet" />
</head>

<body id="page-top">

    <!-- Navigation -->
    <?php
require_once ROOT . '/includes/navbar.php';
?>

    <!-- Masthead -->
    <header class="masthead bg-primary text-white text-center">
        <div class="container d-flex align-items-center flex-column">

            <!-- Masthead Avatar Image -->
            <img class="masthead-avatar mb-5" src="assets/img/avataaars.svg" alt="Smiling guy avatar" />

            <!-- Masthead Heading -->
            <h1 class="masthead-heading text-uppercase mb-0">
                Welcome
            </h1>

            <!-- Icon Divider -->
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon">
                    <em class="fas fa-star"></em>
                </div>
                <div class="divider-custom-line"></div>
            </div>

            <!-- Masthead Subheading -->
            <p class="masthead-subheading font-weight-light mb-0">
                A good day is a good day. A bad day is a good story.
            </p>

        </div>
    </header>

    <!-- About Section -->
    <section class="page-section" id="about">
        <div class="container">

            <!-- About Section Heading -->
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
                About
            </h2>

            <!-- Icon Divider -->
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon">
                    <em class="fas fa-star"></em>
                </div>
                <div class="divider-custom-line"></div>
            </div>

            <!-- About Section Content -->
            <div class="row">
                <div class="col-lg-4 ml-auto">
                    <p class="lead">
                        This is a project undertaken to explore the prowess of PHP as a
                        server-side scripting language. Along with PHP, Bootstrap has been
                        leveraged for styling this website. The end result is a jubilant
                        amalgamation of design with functionality. The website greets the
                        user with a resplendent message to brighten up his or her day.
                    </p>
                </div>
                <div class="col-lg-4 mr-auto">
                    <p class="lead">
                        This page is responsive and dynamic. It contains a navigation bar
                        displaying greetings along with the username. The body of the page
                        contains the about section for illustration and cosmetic purposes.
                        Any number of such sections can be added to elongate the page. The
                        footer has been included with PHP to emphasize on code
                        reusability.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php
require_once ROOT . '/includes/footer.php';
?>

    <!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
    <div class="scroll-to-top d-lg-none position-fixed ">
        <a class="js-scroll-trigger d-blockquote text-center text-white rounded" href="#page-top">
            <em class="fa fa-chevron-up"></em>
        </a>
    </div>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts -->
    <script src="assets/js/welcome.min.js"></script>
</body>

</html>