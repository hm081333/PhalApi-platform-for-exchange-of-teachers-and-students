<?php

class Model_Setting extends PhalApi_Model_NotORM
{
    
    protected function getTableName($id)
    {
        return 'setting';
    }
}
