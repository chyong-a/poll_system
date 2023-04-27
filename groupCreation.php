<?php
require_once('require.php');

$title = "Create a new group";

// main
session_start();

if (!is_admin()) {
    redirect('/index.php');
}

$user_storage = new UserStorage();
$auth = new Auth($user_storage);
if (!$auth->is_authenticated()) {
    redirect("/login.php");
}

$user_storage = new UserStorage();
$all_users = $user_storage->findAll();

//if only one user, this user is admin.
if (count($all_users) <= 1) {
    echo "<script>alert('There is only one user in the DB!'); window.location = '/index.php'</script>";
}

$group_storage = new GroupStorage();
$all_groups = $group_storage->findAll();
$all_group_names = [];

foreach ($all_groups as $group) {
    array_push($all_group_names, $group['groupName']);
}

$errors = [];
$data = [];

if (count($_POST) > 0) {
    if (isset($_POST['groupName'])) {
        if (in_array($_POST['groupName'], $all_group_names)) {
            $errors['global'] = "This name has already been taken!";
        } else {
            if (validateGroup($_POST, $data, $errors)) {
                $group = new Group($group_storage);
                $group->save($data);
                redirect('index.php');
            }
        }
    }
}
?>
<?php
require_once(PATH . 'head.php');
?>
<div id="wrapper">
    <aside>
        <h1>Create a new group</h1>
    </aside>
    <section id="main">
        <?php if (isset($errors['global'])): ?>
        <p><span class="error">
                <?= $errors['global'] ?>
            </span></p>
        <?php endif; ?>
        <p>Please, choose users for a new group</p>
        <form action="" method="post">
            <?php foreach ($all_users as $user): ?>
            <input type="checkbox" name="users[]" id="<?= $user['id'] ?>" value="<?= $user['id'] ?>" <?php if (isset($_POST['users'])) {
                    if (in_array($user['id'], $_POST['users'])) {
                        echo "checked";
                    }
                } ?>>
            <label for="<?= $user['id'] ?>"><?= $user['username'] ?></label><br>
            <?php endforeach ?>
            <?php if (isset($errors['users'])): ?>
            <span class="error">
                <?= $errors['users'] ?>
            </span>
            <?php endif; ?> <br>
            <input type="text" name="groupName" id="groupName" placeholder="Type the name for the current group"
                value="<?= $_POST['groupName'] ?? "" ?>"> <br>
            <?php if (isset($errors['groupName'])): ?>
            <span class="error">
                <?= $errors['groupName'] ?>
            </span>
            <?php endif; ?>
            <br>
            <button type="submit">Create a group</button>
        </form>
    </section>
</div>
<?php
require_once(PATH . 'footer.php');
?>