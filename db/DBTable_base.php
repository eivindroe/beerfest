<?php
namespace Beerfest;

interface DBTable_base
{
    public function getTableName();
    public function getTableColumns();
}// DBTable_base