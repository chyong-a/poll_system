<?php
require_once('require.php');
$title = "Poll system";

//start session
session_start();

//if proceed to vote is pressed, it will go to poll page
if (isset($_GET['id'])) {
    redirect("poll.php/?id=" . $_GET['id']);
}

if (isset($_GET['revote'])) {
    redirect("revote.php/?id=" . $_GET['revote']);
}

//main
require_once(PATH . 'head.php');
?>
<div id="wrapper">
    <aside id="usersInfo">
        <?php if ((count($_SESSION) != 0) && is_admin()) {
                require_once(PATH . "admin/a.sidebar.view.php");
            } else {
                require_once(PATH . "views/sidebar.view.php");
            } ?>
    </aside>

    <section id="main">
        <?php if ((count($_SESSION) != 0) && is_admin()) {
                require_once(PATH . "admin/a.index.view.php");
            } else {
                require_once(PATH . "views/index.view.php");
            } ?>
    </section>
</div>
<?php
    require_once(PATH . 'footer.php');

    ?>