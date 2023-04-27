<?php
require_once('require.php');
class GroupStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO(PATH . 'data/groups.json'));
    }
}