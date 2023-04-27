<?php

$poll_storage = new PollStorage();
$polls = $poll_storage->findAll();

//recent poll
$recentPoll = $poll_storage->findNewestButNotClosed();
if (isset($recentPoll)) {
    echo "<div id='recentPoll'>Recent poll";
    display_poll_on_index($recentPoll);
    echo "</div>";
} else {
    echo "<div id='recentPoll'>Recent poll<p>There are no recent polls yet</p></div>";
}

//active polls
$activePolls = $poll_storage->findActiveButRecentPolls();
if (count($activePolls) > 0) {
    echo "<div id='activePolls'>Active polls";
    foreach ($activePolls as $poll) {
        display_poll_on_index($poll);
    }
    echo "</div>";
} else {
    echo "<div id='activePolls'>Active polls<p>There are no active polls yet</p></div>";
}

//closed polls
$closedPolls = $poll_storage->findClosedPolls();
if (count($closedPolls) > 0) {
    echo "<div id='closedPolls'>Closed polls";
    foreach ($closedPolls as $poll) {
        display_closed_poll_on_index($poll);
    }
    echo "</div>";
} else {
    echo "<div id='closedPolls'>Closed polls<p>There are no closed polls yet</p></div>";

}

//special for the groups
if (isset($_SESSION['user'])) {
    $allSpecialPolls = $poll_storage->findSpecialPolls();
    $specialPolls = [];
    $userId = $_SESSION['user']['id'] ?? "";
    $group_storage = new GroupStorage();

    foreach ($allSpecialPolls as $poll) {
        $allGroupsOfPoll = $poll['groups'];
        foreach ($allGroupsOfPoll as $group) {
            $groupObject = $group_storage->findById($group);
            if (in_array($userId, $groupObject['users'] ?? [])) {
                array_push($specialPolls, $poll);
            }
        }
    }

    if (count($specialPolls) > 0) {
        echo "<div id='specialPolls'>Special polls for your group";
        foreach ($specialPolls as $poll) {
            if ($poll['deadline'] > date("Y-m-d")) {
                display_poll_on_index($poll);
            } else {
                display_closed_poll_on_index($poll);
            }
        }
        echo "</div>";
    } else {
        echo "<div id='specialPolls'>Special polls for your group<p>There are no special polls for your group yet</p></div>";
    }
}