<?php
require_once('require.php');

$title = "Create a new poll";

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

$poll_storage = new PollStorage();

$pollClass = new Poll($poll_storage);

$errors = [];
$data = [];

if (count($_POST) > 0) {
    if (validatePoll($_POST, $data, $errors)) {
        if ($pollClass->is_duplicate($data)) {
            $errors['global'] = "This poll already exists in the system";
        } else {
            if (isset($_POST['groups'])) {
                $data['groups'] = $_POST['groups'];
            }
            $pollClass->save($data);
            redirect('index.php');
        }
    }
}

//however group is not mandatory
$group_storage = new GroupStorage();
$all_groups = $group_storage->findAll();
?>
<?php
require_once(PATH . 'head.php');
?>
<div id="wrapper">
    <aside>
        <h1>Create a new poll</h1>
    </aside>
    <section id="main">
        <?php if (isset($errors['global'])): ?>
        <p><span class="error">
                <?= $errors['global'] ?>
            </span></p>
        <?php endif; ?>
        <form action="" method="post">
            <div>
                <label for="question">Question: </label><br>
                <input type="text" name="question" id="question" placeholder="Type the question for the poll"
                    value="<?= $_POST['question'] ?? "" ?>">
                <?php if (isset($errors['question'])): ?>
                <span class="error">
                    <?= $errors['question'] ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <label for="options">Please, enter the options separated by semicolon ';'. Don't put semicolon at
                    the end.<br> E.g., 'yes; no' </label><br>
                <input type="text" name="options" id="options" placeholder="Type the options separated by comma"
                    value="<?= $_POST['options'] ?? "" ?>">
                <?php if (isset($errors['options'])): ?>
                <span class="error">
                    <?= $errors['options'] ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <label for="question">Is it a multiple choice question? </label><br>
                <input type="radio" id="true" name="isMultiple" value="true">
                <label for="true">Yes</label><br>
                <input type="radio" id="false" name="isMultiple" value="false">
                <label for="false">No</label><br>
                <?php if (isset($errors['isMultiple'])): ?>
                <span class="error">
                    <?= $errors['isMultiple'] ?>
                </span>
                <?php endif; ?>
            </div>
            <div>
                <label for="deadline">Deadline: </label><br>
                <input type="date" name="deadline" id="deadline" value="<?= $_POST['deadline'] ?? "" ?>">
                <?php if (isset($errors['deadline'])): ?>
                <span class="error">
                    <?= $errors['deadline'] ?>
                </span>
                <?php endif; ?>
            </div>
            <?php if (count($all_groups) > 0): ?>
            <div>
                <label for="group">Group: </label><br>
                <?php foreach ($all_groups as $group): ?>
                <input type="checkbox" name="groups[]" id="<?= $group['id'] ?>" value="<?= $group['id'] ?>" <?php if (isset($_POST['groups'])) {
                        if (in_array($group['id'], $_POST['groups'])) {
                            echo "checked";
                        }
                    } ?>>
                <label for="<?= $group['id'] ?>"><?= $group['groupName'] ?></label><br>
                <?php endforeach ?>
            </div>
            <?php endif ?>
            <button type="submit">Save a poll</button>
        </form>
    </section>
</div>
<?php
require_once(PATH . 'footer.php');
?>