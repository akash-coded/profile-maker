<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}
define('__ROOT__', dirname(__FILE__));
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Welcome page upon successfully logging in" />
    <meta name="robots" content="index, follow" />
    <meta name="author" content="Akash Das" />
    <title>Welcome | Profile Maker</title>
    <link rel="icon" href="favicon.png" type="image/png" sizes="16x16" />
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet"
        type="text/css" />
    <link href="vendor/bootstrap/css/bootstrap-tdoc.min.css" rel="stylesheet" />
</head>

<body id="page-top">
    <?php
    require_once(__ROOT__ . '/includes/navbar.php');
    ?>
    <header class="masthead bg-primary text-white text-center">
        <div class="container d-flex align-items-center flex-column">
            <img class="masthead-avatar mb-5" src="assets/img/avataaars.svg" alt="Smiling guy avatar" />
            <h1 class="masthead-heading text-uppercase mb-0">
                Welcome
            </h1>
            <div class="divider-custom divider-light">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="divider-custom-line"></div>
            </div>
            <p class="masthead-subheading font-weight-light mb-0">
                A good day is a good day. A bad day is a good story.
            </p>
        </div>
    </header>

    <section class="page-section" id="about">
        <div class="container">
            <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">
                About
            </h2>
            <div class="divider-custom">
                <div class="divider-custom-line"></div>
                <div class="divider-custom-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="divider-custom-line"></div>
            </div>
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

    <?php
    require_once(__ROOT__ . '/includes/footer.php');
    ?>

    <div class="scroll-to-top d-lg-none position-fixed ">
        <a class="js-scroll-trigger d-block text-center text-white rounded" href="#page-top">
            <i class="fa fa-chevron-up"></i>
        </a>
    </div>

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/welcome.min.js"></script>
</body>

</html>