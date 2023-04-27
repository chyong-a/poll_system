<?php
$poll_storage = new PollStorage();
$polls = $poll_storage->findAll();
$chosenPoll = $_GET['id'];

$closedPolls = $poll_storage->findClosedPolls();
$currentPoll = getTheChosenPoll($chosenPoll, $polls);
$currentUser = $_SESSION['user'];

//check if the current poll is closed
foreach ($closedPolls as $closedPoll) {
    if ($closedPoll['id'] == $chosenPoll) {
        redirect('index.php');
    }
}

//check if the current poll was found in the DB
//check if the current user voted (because it's for REvoting)
if (isset($currentPoll)) {
    if (!in_array($_SESSION['user']['id'], $currentPoll['voted'])) {
        redirect('index.php');
    }
} else {
    redirect('index.php');
}

//rewrite the data (revote itself)
if (isset($_GET['userid']) && isset($_GET['answers']) && isset($_GET['id'])) {
    $oldVoted = $currentPoll['voted'];
    $index = array_search($_GET['userid'], $oldVoted);
    unset($oldVoted[$index]);
    //now there is no current user
    $newVoted = array_values($oldVoted);
    array_push($newVoted, $_GET['userid']);

    $oldAnswers = $currentPoll['answers'];
    unset($oldAnswers[$index]);
    $newAnswers = array_values($oldAnswers);
    array_push($newAnswers, $_GET['answers']);

    $data = [
        'question' => $currentPoll['question'],
        'options' => $currentPoll['options'],
        'isMultiple' => $currentPoll['isMultiple'],
        'createdAt' => $currentPoll['createdAt'],
        'deadline' => $currentPoll['deadline'],
        'answers' => $newAnswers,
        'voted' => $newVoted,
        'id' => $currentPoll['id']
    ];

    if (isset($currentPoll['groups'])) {
        $data['groups'] = $currentPoll['groups'];
    }

    $poll_storage = new PollStorage();
    $poll_storage->update($currentPoll['id'], $data);

    echo "<script>alert('Your vote has been successfully changed!'); window.location = '/index.php'</script>";
    // redirect('/index.php');
} else if (isset($_GET['userid']) && isset($_GET['id']) && !isset($_GET['answers'])) {
    echo "<script>alert('You should choose the answer!');</script>";
}

//type of poll
if ($currentPoll['isMultiple'] == "true") {
    display_multiple_poll_to_vote($currentPoll);
} else {
    display_not_multiple_poll_to_vote($currentPoll);
}

echo '<a href="index.php"><button>To polls</button></a>';