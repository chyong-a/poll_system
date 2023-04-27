<?php
$poll_storage = new PollStorage();
$polls = $poll_storage->findAll();
$chosenPoll = $_GET['id'];

$closedPolls = $poll_storage->findClosedPolls();
$currentPoll = getTheChosenPoll($chosenPoll, $polls);

//check if the opened poll is closed.
foreach ($closedPolls as $closedPoll) {
    if ($closedPoll['id'] == $chosenPoll) {
        redirect('index.php');
    }
}

//first check if the current poll is set(could be found) 
//and then check if the current user already voted for the current poll
if (isset($currentPoll)) {
    if (in_array($_SESSION['user']['id'], $currentPoll['voted'])) {
        redirect('index.php');
    }
} else {
    redirect('index.php');
}

//check if the current user is in the group of the current poll
if (isset($currentPoll['groups'])) {
    $group_storage = new GroupStorage();
    $allGroupOfPoll = $currentPoll['groups'];
    foreach ($allGroupOfPoll as $group) {
        $groupObject = $group_storage->findById($group);
        if (!in_array($_SESSION['user']['id'], $groupObject['users']) && (!is_admin())) {
            redirect('index.php');
        }
    }
}

//records the choice
if (isset($_GET['userid']) && isset($_GET['answers']) && isset($_GET['id'])) {
    $data = [
        'id' => $_GET['id'],
        'answers' => $_GET['answers'],
        'userid' => $_GET['userid'],
    ];
    $pollClass = new Poll($poll_storage);
    $pollClass->addVote($data);
    echo "<script>alert('Your vote has been successfully saved!'); window.location = '/index.php'</script>";
    // if (isset($_GET['answers'])) {
    //     redirect('/index.php');
    // }
} else if (isset($_GET['userid']) && isset($_GET['id']) && !isset($_GET['answers'])) {
    echo "<script>alert('You should choose the answer!');</script>";
}

//check type of poll
if ($currentPoll['isMultiple'] == "true") {
    display_multiple_poll_to_vote($currentPoll);
} else {
    display_not_multiple_poll_to_vote($currentPoll);
}

echo '<a href="index.php"><button>To polls</button></a>';