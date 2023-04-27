<?php
require_once('require.php');
class UserStorage extends Storage
{
    public function __construct()
    {
        parent::__construct(new JsonIO(PATH.'data/users.json'));
    }
}