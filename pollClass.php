<?php
class Poll
{
    private $poll_storage;
    private $poll = NULL;

    public function __construct(IStorage $poll_storage)
    {
        $this->poll_storage = $poll_storage;

        if (count($_POST) > 0) {
            $this->poll = $_POST;
        }
    }

    public function save($data)
    {
        $poll = [
            'question' => $data['question'],
            'options' => $data['options'],
            'isMultiple' => $data['isMultiple'],
            'createdAt' => date("Y-m-d"),
            'deadline' => $data['deadline'],
            'answers' => [],
            'voted' => [],
        ];
        if (isset($data['groups'])) {
            $poll['groups'] = $data['groups'];
        }

        return $this->poll_storage->add($poll);
    }

    public function update($id, $data, $currentPoll)
    {
        $poll = [
            'question' => $data['question'],
            'options' => $data['options'],
            'isMultiple' => $data['isMultiple'],
            'createdAt' => $currentPoll['createdAt'],
            'deadline' => $data['deadline'],
            'answers' => $currentPoll['answers'],
            'voted' => $currentPoll['voted'],
            'id' => $id
        ];
        if (isset($data['groups'])) {
            $poll['groups'] = $data['groups'];
        }
        return $this->poll_storage->update($id, $poll);
    }

    public function addVote($data)
    {
        $all = $this->poll_storage->findAll();
        $record = []; //initial
        foreach ($all as $poll) {
            if ($poll['id'] == $data['id']) {
                $record = [
                    'question' => $poll['question'],
                    'options' => $poll['options'],
                    'isMultiple' => $poll['isMultiple'],
                    'createdAt' => $poll['createdAt'],
                    'deadline' => $poll['deadline'],
                    'answers' => $poll['answers'],
                    'voted' => $poll['voted'],
                    'id' => $poll['id'],
                ];
                if (isset($poll['groups'])) {
                    $record['groups'] = $poll['groups'];
                }
                break;
            }
        } //caught data

        array_push($record['answers'], $data['answers']);
        array_push($record['voted'], $data['userid']);
        return $this->poll_storage->update($data['id'], $record);
    }

    public function is_duplicate($poll_to_compare)
    {
        $polls = $this->poll_storage->findAll();
        $result = false;
        foreach ($polls as $poll) {
            if ($this->is_the_same($poll_to_compare, $poll)) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    private function is_the_same($poll1, $poll2)
    {
        return $poll1['question'] == $poll2['question'] && $poll1['options'] == $poll2['options'] && $poll1['isMultiple'] == $poll2['isMultiple'] && $poll1['createdAt'] == $poll2['createdAt'] && $poll1['deadline'] == $poll2['deadline'];
    }

}