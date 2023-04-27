<?php
require_once('require.php');

class PollStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO(PATH . 'data/polls.json'));
    }

    public function findNewestPoll()
    {
        $all_polls = [];
        $all = $this->findAll();
        foreach ($all as $poll) {
            if (!isSpecial($poll)) {
                array_push($all_polls, $poll);
            }
        }
        if (count($all_polls) > 0) {
            $date = array_column($all_polls, 'createdAt');
            array_multisort($date, SORT_DESC, $all_polls);
            return reset($all_polls);
        } else
            return;
    }

    public function findActivePolls()
    {
        $all_polls = [];
        $all = $this->findAll();
        foreach ($all as $poll) {
            if (!isSpecial($poll)) {
                array_push($all_polls, $poll);
            }
        }

        $result = [];
        foreach ($all_polls as $poll) {
            if ($poll['deadline'] > date("Y-m-d")) {
                array_push($result, $poll);
            }
        }
        $date = array_column($result, 'createdAt');
        array_multisort($date, SORT_DESC, $result);
        return $result;
    }

    public function findActiveButRecentPolls()
    {
        $all = $this->findActivePolls();
        $result = [];
        foreach ($all as $poll) {
            if ($poll != $this->findNewestButNotClosed()) {
                array_push($result, $poll);
            }
        }
        return $result;
    }

    public function findClosedPolls()
    {
        $all_polls = [];
        $all = $this->findAll();
        foreach ($all as $poll) {
            if (!isSpecial($poll)) {
                array_push($all_polls, $poll);
            }
        }

        $result = [];
        foreach ($all_polls as $poll) {
            if ($poll['deadline'] <= date("Y-m-d")) {
                array_push($result, $poll);
            }
        }
        $date = array_column($result, 'createdAt');
        array_multisort($date, SORT_DESC, $result);
        return $result;
    }

    public function findClosedButRecentPolls()
    {
        $all = $this->findClosedPolls();
        $result = [];
        foreach ($all as $poll) {
            if ($poll != $this->findNewestPoll()) {
                array_push($result, $poll);
            }
        }
        $date = array_column($result, 'createdAt');
        array_multisort($date, SORT_DESC, $result);
        return $result;
    }

    public function findNewestButNotClosed()
    {
        $all_active = $this->findActivePolls();

        if (count($all_active) > 0) {
            $date = array_column($all_active, 'createdAt');
            array_multisort($date, SORT_DESC, $all_active);
            return reset($all_active);
        } else
            return;
    }

    //for specific groups
    public function findSpecialPolls()
    {
        $result = [];
        $all = $this->findAll();
        foreach ($all as $poll) {
            if (isSpecial($poll)) {
                array_push($result, $poll);
            }
        }
        $date = array_column($result, 'createdAt');
        array_multisort($date, SORT_DESC, $result);
        return $result;
    }

}