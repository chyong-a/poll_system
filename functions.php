<?php

function redirect($url)
{
    header("Location: $url");
    die();
}

function validate($post, &$data, &$errors)
{
    if (!isset($post["username"])) {
        $errors['username'] = "Username is missing!";
    } else if (trim($post["username"]) === "") {
        $errors['username'] = "Username is empty!";
    } else {
        $data["username"] = $post["username"];
    }

    if (!isset($post["password"])) {
        $errors['password'] = "Password is missing!";
    } else if (trim($post["password"]) === "") {
        $errors['password'] = "Password is empty!";
    } else {
        $data["password"] = $post["password"];
    }

    if (isset($post["fullname"])) {
        if (trim($post["fullname"]) === "") {
            $errors['fullname'] = "Full name is empty!";
        } else {
            $data["fullname"] = $post["fullname"];
        }
    }

    return count($errors) === 0;
}

function validatePoll($post, &$data, &$errors)
{
    if (!isset($post["question"])) {
        $errors['question'] = "The question field cannot be empty!";
    } else if (trim($post["question"]) === "") {
        $errors['question'] = "The question field cannot be empty!";
    } else {
        $data["question"] = $post["question"];
    }

    if (!isset($post["options"])) {
        $errors['options'] = "The options are missing!";
    } else if (trim($post["options"]) === "") {
        $errors['options'] = "The options field is empty!";
    } else {
        $data["options"] = $post["options"];
    }

    if (!isset($post["isMultiple"])) {
        $errors['isMultiple'] = "Is it a multiple choice question?";
    } else if (trim($post["isMultiple"]) === "") {
        $errors['isMultiple'] = "Is it a multuple choice question?";
    } else {
        $data["isMultiple"] = $post["isMultiple"];
    }

    if (!isset($post["deadline"])) {
        $errors['deadline'] = "Please, choose the deadline!";
    } else if (trim($post["deadline"]) === "") {
        $errors['deadline'] = "The deadline field is empty!";
    } else {
        $data["deadline"] = $post["deadline"];
    }

    return count($errors) == 0;
}

function validateGroup($post, &$data, &$errors)
{
    if (!isset($post["users"])) {
        $errors['users'] = "Please, choose at least one user!";
    } else {
        $data["users"] = $post["users"];
    }

    if (!isset($post["groupName"])) {
        $errors['groupName'] = "The name field is empty!";
    } else if (trim($post["groupName"]) === "") {
        $errors['groupName'] = "Please, fill out the name field!";
    } else {
        $data["groupName"] = $post["groupName"];
    }

    return count($errors) == 0;
}

function is_admin()
{
    if (isset($_SESSION['user']['roles'])) {
        return in_array('admin', $_SESSION['user']['roles']);
    }
}

function display_poll_on_index($poll)
{
    echo "<div class='pollframe'>";
    echo "<form action='' method='get'>";
    echo "The ID: " . $poll['id'] . "<br>";
    echo "The time of creation: " . $poll['createdAt'] . "<br>";
    echo "The voting deadline: " . $poll['deadline'] . "<br>";
    if (isset($_SESSION['user'])) {
        if (in_array($_SESSION['user']['id'], $poll['voted'])) {
            echo "<p class='voted'>You have already voted in this poll. Would you like to change your choice?</p>";
            echo "<input type='hidden' name='revote' value=" . $poll['id'] . ">" . "<input type='submit' value='Proceed to revote'>";
        } else {
            echo "<input type='hidden' name='id' value=" . $poll['id'] . ">" . "<input type='submit' value='Proceed to vote'>";
        }
    } else {
        echo "<input type='hidden' name='id' value=" . $poll['id'] . ">" . "<input type='submit' value='Proceed to vote'>";
    }
    echo "</form>";
    echo "</div>";
}

function display_closed_poll_on_index($poll)
{
    echo "<div class='pollframe'>";
    echo "<form action='' method='get'>";
    echo "The ID: " . $poll['id'] . "<br>";
    echo "The time of creation: " . $poll['createdAt'] . "<br>";
    echo "The voting deadline: " . $poll['deadline'] . "<br>";
    echo "The vote has been closed.<br>";
    if ($poll['isMultiple'] == 'true') {
        if (countResultsForMultiple($poll) == "") {
            echo "There were not enough votes.";
        } else {
            echo "Results: <br>" . countResultsForMultiple($poll);
        }
    } else {
        if (countResultsForNotMultiple($poll) == "") {
            echo "There were not enough votes.";
        } else {
            echo "Results: <br>" . countResultsForNotMultiple($poll);
        }
    }
    echo "</form>";
    echo "</div>";
}

function getTheChosenPoll($chosenPoll, $polls)
{
    foreach ($polls as $poll) {
        if (isset($poll['id']) && $poll['id'] == $chosenPoll) {
            return $poll;
        }
    }
}

//using get because we will catch the current poll with get, otherwise it will redirect to index.
function display_multiple_poll_to_vote($poll)
{
    echo "<form action='' method='get'>";
    echo "The description: " . $poll['question'] . "<br>";
    $options = explode(";", $poll['options']);
    echo "Multiple choice is allowed and encouraged!<br>The options: <br>";
    foreach ($options as $option) {
        echo "<input type='checkbox' id='" . $option . "' name='answers[]' value='" . $option . "'>";
        echo '<label for="' . $option . '">' . $option . '</label><br>';
    }
    echo "<br>";
    echo "The voting deadline: " . $poll['deadline'] . "<br>";
    echo "The time of creation: " . $poll['createdAt'] . "<br>";
    echo "<input type='hidden' name='userid' value=" . $_SESSION['user']['id'] . ">";
    echo "<input type='hidden' name='id' value=" . $poll['id'] . ">";
    echo "<input type='submit' value='Confirm the choice'>";
    echo "</form>";
}

function display_not_multiple_poll_to_vote($poll)
{
    echo "<form action='' method='get'>";
    echo "The description: " . $poll['question'] . "<br>";
    $options = explode(";", $poll['options']);
    echo "Single choice is only possible!<br>The options: <br>";
    foreach ($options as $option) {
        echo "<input type='radio' id='" . $option . "' name='answers' value='" . $option . "'>";
        echo '<label for="' . $option . '">' . $option . '</label><br>';
    }
    echo "<br>";
    echo "The voting deadline: " . $poll['deadline'] . "<br>";
    echo "The time of creation: " . $poll['createdAt'] . "<br>";
    echo "<input type='hidden' name='id' value=" . $poll['id'] . ">";
    echo "<input type='hidden' name='userid' value=" . $_SESSION['user']['id'] . ">";
    echo "<input type='submit' value='Confirm the choice'>";
    echo "</form>";
}

//for not multiple

function countResultsForNotMultiple($poll)
{
    $votes = $poll['answers'];
    $uniqueVotes = array_unique($votes);
    $result = "";
    $resultsQuant = [];
    foreach ($uniqueVotes as $vote) {
        $counter = 0;
        foreach ($votes as $originalVote) {
            if ($vote == $originalVote) {
                $counter++;
            }
        }
        array_push($resultsQuant, $counter);
        $result = $result . $vote . " - " . $counter . '<br>';
    }
    if ($result != "") {
        $maxIndex = array_search(max($resultsQuant), $resultsQuant);
        $result = $result . '<span class="winner">' . $uniqueVotes[$maxIndex] . ' has won this poll! </span><br>';
    }
    return $result;
}

//for multiple choice

function countResultsForMultiple($poll)
{
    $all_votes = [];

    foreach ($poll['answers'] as $answer) {
        foreach (array_values($answer) as $ans) {
            array_push($all_votes, $ans);
        }
    }

    $uniqueVotes = array_unique($all_votes);
    $result = "";
    $resultsQuant = [];
    foreach ($uniqueVotes as $vote) {
        $counter = 0;
        foreach ($all_votes as $originalVote) {
            if ($vote == $originalVote) {
                $counter++;
            }
        }
        array_push($resultsQuant, $counter);
        $result = $result . $vote . " - " . $counter . '<br>';
    }
    if ($result != "") {
        $maxIndex = array_search(max($resultsQuant), $resultsQuant);
        $result = $result . '<span class="winner">' . $uniqueVotes[$maxIndex] . ' has won this poll! </span><br>';
    }
    return $result;
}

function isSpecial($poll)
{
    return isset($poll['groups']);
}