<?php
$title = "Revote page";
session_start();

require_once('require.php');

if (!isset($_SESSION['user'])) {
    redirect('/login.php');
}

if (!isset($_GET['id'])) {
    redirect('/index.php');
}

?>
<?php
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
            <?php require_once(PATH . "views/revote.view.php"); ?>
        </section>
    </div>
    <?php
    require_once(PATH . 'footer.php');
    ?>