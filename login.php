<?php
require_once('require.php');
$title = "Login page";

// main
session_start();
if (isset($_SESSION['user']['username'])) {
    redirect("index.php");
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
$data = [];
$errors = [];

if ($_POST) {
    if (validate($_POST, $data, $errors)) {
        $auth_user = $auth->authenticate($data['username'], $data['password']);
        if (!$auth_user) {
            $errors['global'] = "Login error";
        } else {
            $auth->login($auth_user);
            redirect('index.php');
        }
    }
}
?>
<?php
require_once(PATH . 'head.php');
?>
<div id="wrapper">
    <aside>
        <h1>Login</h1>
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
                <button type="submit">Login</button>
                <a href="register.php">Sign up</a>
            </div>
        </form>
    </section>
</div>
<?php
    require_once(PATH . 'footer.php');
    ?>