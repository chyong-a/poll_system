<?php
require_once('require.php');
$title = 'Vote page';
session_start();

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
        <?php require_once(PATH . "views/poll.view.php"); ?>
    </section>
</div>
<?php
require_once(PATH . 'footer.php');
?>