<?php

class Model_Message extends PhalApi_Model_NotORM
{
    
    protected function getTableName($id)
    {
        return 'message';
    }
}
