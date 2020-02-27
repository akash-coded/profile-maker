<nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
    <div class="container">
        <a class="navbar-brand js-scroll-trigger" href="#page-top">Hello
            <?php echo $_SESSION['user'] ?></a>
        <button
            class="navbar-toggler navbar-toggler-right text-uppercase font-weight-bold bg-primary text-white rounded"
            type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive"
            aria-expanded="false" aria-label="Toggle navigation">
            Menu
            <em class="fas fa-bars"></em>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-1 px-0 px-lg-3 rounded" href="<?php $dest_page =
                                                                            ($_SERVER['PHP_SELF']) == '/welcome.php' ? 'profile.php' : 'welcome.php';
                                                                        echo $dest_page; ?>"><?php echo ('profile.php' == $dest_page) ? 'Profile' : 'Welcome';
                                                                                                ?></a>
                </li>
                <li class="nav-item mx-0 mx-lg-1">
                    <a class="nav-link py-1 px-0 px-lg-3 rounded" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>