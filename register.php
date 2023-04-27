<?php
require_once('require.php');

$title = "Registration page";

// main
if (isset($_SESSION['user']['username'])) {
    redirect("index.php");
}


$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$errors = [];
$data = [];
if (count($_POST) > 0) {
    if (validate($_POST, $data, $errors)) {
        if ($auth->user_exists($data['username'])) {
            $errors['global'] = "User already exists";
        } else {
            $auth->register($data);
            echo "<script>alert('You have successfully registred!'); window.location = '/login.php'</script>";
            // redirect('login.php');
        }
    }
}

?>
<?php
require_once(PATH . 'head.php');
?>
<div id="wrapper">
    <aside>
        <h1>Registration</h1>
    </aside>
    <section id="main">
        <?php if (isset($errors['global'])): ?>
        <p><span class="error">
                <?= $errors['global'] ?>
            </span></p>
        <?php endif; ?>
        <form action="" method="post">
            <div>
                <label for="username">Username: </label><br>
                <input type="text" name="username" id="username" placeholder="Type your username"
                    value="<?= $_POST['username'] ?? "" ?>">
                <?php if (isset($errors['username'])): ?>
                <span class="error">
                    <?= $errors['username'] ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <label for="password">Password: </label><br>
                <input type="password" name="password" id="password" placeholder="Type your password"
                    value="<?= $_POST['password'] ?? "" ?>">
                <?php if (isset($errors['password'])): ?>
                <span class="error">
                    <?= $errors['password'] ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <label for="fullname">Full name: </label><br>
                <input type="text" name="fullname" id="fullname" placeholder="Type your full name"
                    value="<?= $_POST['fullname'] ?? "" ?>">
                <?php if (isset($errors['fullname'])): ?>
                <span class="error">
                    <?= $errors['fullname'] ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <button type="submit">Register</button>
                <a href="login.php">Login</a>
            </div>
        </form>
    </section>
</div>
<?php
require_once(PATH . 'footer.php');
?>