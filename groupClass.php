<?php
class Group
{
    private $group_storage;
    private $group = NULL;

    public function __construct(IStorage $group_storage)
    {
        $this->group_storage = $group_storage;

        if (count($_POST) > 0) {
            $this->group = $_POST;
        }
    }

    public function save($data)
    {
        $group = [
            'users' => $data['users'],
            'groupName' => $data['groupName']
        ];
        return $this->group_storage->add($group);
    }

}