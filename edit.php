<?php
require_once('require.php');

$title = "Edit the poll";

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

$currentPollID = $_GET['id'];
$currentPoll = $poll_storage->findById($currentPollID);

if (!isset($currentPoll)) {
    redirect('/index.php');
}

print_r($currentPoll);
echo "___________<br>";
print_r($_POST);

if (count($_POST) > 0) {
    if ((($_POST['isMultiple'] != $currentPoll['isMultiple']) || ($_POST['options'] != $currentPoll['options']) || ($_POST['question'] != $currentPoll['question'])) && (count($currentPoll['answers']) != 0)) {
        $errors['global'] = 'Some people have already voted. It is better to create a new poll at this point!';
    } else {
        if (validatePoll($_POST, $data, $errors)) {
            if (isset($_POST['groups'])) {
                $data['groups'] = $_POST['groups'];
            }
            $pollClass->update($currentPollID, $data, $currentPoll);
            echo "<script>alert('The poll has been successfully updated!'); window.location = '/index.php'</script>";
            // redirect('/index.php');
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
        <h1>Edit the poll</h1>
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
                <input type="text" name="question" id="question" placeholder="Type the question for the poll" value="<?php
                if (isset($_POST['question'])) {
                    if ($_POST['question'] != $currentPoll['question']) {
                        echo $_POST['question'];
                    } else {
                        echo $_POST['question'];
                    }
                } else {
                    echo $currentPoll['question'];
                } ?>">
                <?php if (isset($errors['question'])): ?>
                    <span class="error">
                        <?= $errors['question'] ?>
                    </span>
                    <?php endif; ?>
            </div>
            <div>
                <label for="options">Please, enter the options separated by comma: </label><br>
                <input type="text" name="options" id="options" placeholder="Type the options separeted by comma" value="<?php
                if (isset($_POST['options'])) {
                    if ($_POST['options'] != $currentPoll['options']) {
                        echo $_POST['options'];
                    } else {
                        echo $_POST['options'];
                    }
                } else {
                    echo $currentPoll['options'];
                } ?>">
                <?php if (isset($errors['options'])): ?>
                    <span class="error">
                        <?= $errors['options'] ?>
                    </span>
                    <?php endif; ?>
            </div>
            <div>
                <label for="question">Is it a multiple choice question? </label><br>
                <input type="radio" id="true" name="isMultiple" value="true" <?php if ($currentPoll['isMultiple'] == 'true') {
                echo "checked";
            } ?><?php if (isset($_POST['isMultiple'])) {
             if ($_POST['isMultiple'] != $currentPoll['isMultiple']) {
                 if ($_POST['isMultiple'] == 'true') {
                     echo 'checked';
                 }
             }
         } ?>>
                <label for="true">Yes</label><br>
                <input type="radio" id="false" name="isMultiple" value="false" <?php if ($currentPoll['isMultiple'] == 'false') {
                echo "checked";
            } ?><?php if (isset($_POST['isMultiple'])) {
             if ($_POST['isMultiple'] != $currentPoll['isMultiple']) {
                 if ($_POST['isMultiple'] == 'false') {
                     echo 'checked';
                 }
             }
         } ?>>
                <label for="false">No</label><br>
                <?php if (isset($errors['isMultiple'])): ?>
                    <span class="error">
                        <?= $errors['isMultiple'] ?>
                    </span>
                    <?php endif; ?>
            </div>
            <div>
                <label for="deadline">Deadline: </label><br>
                <input type="date" name="deadline" id="deadline" value="<?php
                if (isset($_POST['deadline'])) {
                    if ($_POST['deadline'] != $currentPoll['deadline']) {
                        echo $_POST['deadline'];
                    } else {
                        echo $_POST['deadline'];
                    }
                } else {
                    echo $currentPoll['deadline'];
                } ?>">
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
                        <input type="checkbox" name="groups[]" id="<?= $group['id'] ?>" value="<?= $group['id'] ?>" <?php if (isset($currentPoll['groups'])) {
                            if (in_array($group['id'], $currentPoll['groups'])) {
                                echo "checked";
                            }
                        } ?>>
                        <label for="<?= $group['id'] ?>">
                            <?= $group['groupName'] ?>
                        </label><br>
                        <?php endforeach ?>
                </div>
                <?php endif ?>
            <button type="submit">Edit the poll</button>
        </form>
    </section>
</div>

<?php
require_once(PATH . 'footer.php');
?>