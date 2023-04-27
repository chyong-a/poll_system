<?php
require_once('require.php');

$title = "Delete the poll";

// main
session_start();

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
if (!$auth->is_authenticated()) {
    redirect("/login.php");
}
if (!is_admin()) {
    redirect('/index.php');
}

$poll_storage = new PollStorage();
$pollClass = new Poll($poll_storage);

$errors = [];
$data = [];

$currentPollID;

if (isset($_GET['id'])) {
    $currentPollID = $_GET['id'];
    $currentPoll = $poll_storage->findById($currentPollID);
} else {
    redirect('/index.php');
}

if (isset($_GET['id'])) {
    if ($poll_storage->findById($currentPollID)) {
    } else {
        redirect('/index.php');
    }
}


if (count($_POST) > 0) {
    $poll_storage->delete($currentPollID);
    redirect('/index.php');
}

?>
<?php
require_once(PATH . 'head.php');
?>
<div id="wrapper">
    <aside>
        <h1>Delete the poll</h1>
    </aside>
    <section id="main">
        <form action="" method="post">
            <label for="question">ID of the poll: </label><br>
            <?php echo "<span>" . $currentPoll['id'] . "</span><br><br>"; ?>
            <label for="question">Question: </label><br>
            <?php echo "<span>" . $currentPoll['question'] . "</span><br><br>"; ?>
            <label for="options">Options: </label><br>
            <?php echo "<span>" . $currentPoll['options'] . "</span><br><br>"; ?>
            <label for="question">Is it a multiple choice question? </label><br>
            <?php echo "<span>" . $currentPoll['isMultiple'] . "</span><br><br>"; ?>
            <label for="deadline">Creation time: </label><br>
            <?php echo "<span>" . $currentPoll['createdAt'] . "</span><br><br>"; ?>
            <label for="deadline">Deadline: </label><br>
            <?php echo "<span>" . $currentPoll['deadline'] . "</span><br><br>"; ?>
            <input type='hidden' name='id' value="<?= $currentPoll['id'] ?>">
            <button type="submit">Confirm the deletion of the poll</button>
        </form>
    </section>
</div>

<?php
    require_once(PATH . 'footer.php');
    ?>