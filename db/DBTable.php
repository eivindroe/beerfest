<?php
namespace Beerfest;

use Beerfest\DBTable_base;
use Beerfest\Database;

abstract class DBTable implements DBTable_base
{
    /**
     * Required column constant
     * @var string
     */
    const DB_REQUIRED = 'required';
    const DB_TYPE = 'type';
    const DB_TYPE_INT = 'int';
    const DB_TYPE_BIGINT = 'bigint';
    const DB_TYPE_DOUBLE = 'double';
    const DB_TYPE_VARCHAR = 'varchar';
    const DB_TYPE_BOOL = 'int';
    const DB_SIZE = 'size';
    const DB_PRIMARY = 'primary';
    const DB_AUTO_INCREMENT = 'auto_increment';
    const DB_UNIQUE = 'unique';


    /**
     * Required columns
     * @var string
     */
    const COL_DELETED = 'deleted';

    /**
     * Database object
     * @var \Beerfest\Database
     */
    private $objDb;

    /**
     * Constructor
     *
     * @since 11. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->objDb = new Database();
        $this->checkTable();
        return $this;
    }// __construct


    /**
     * Get table name
     *
     * @since 11. February 2014, v. 1.00
     * @return string Table name
     */
    public function getTableName()
    {
    }// getTableName


    /**
     * Get table columns
     *
     * @since 11. February 2014, v. 1.00
     * @return array Table columns
     */
    public function getTableColumns()
    {
    }// getTableColumns


    /**
     * Check table - create if not exists
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    private function checkTable()
    {
        $blnExists = mysqli_query($this->getConnection(), 'SHOW TABLES LIKE ' . $this->getTableName());
        if($blnExists === false)
        {
            // Create table
            $this->createTable();
        }
        else
        {
            // @TODO Check if table has changed, if it has alter table
        }
    }// checkTable


    /**
     * Create table - creates database table
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    private function createTable()
    {
        $aryColumns = array();
        $aryTableColumns = $this->getTableColumns();
        $aryPrimary = array();

        foreach($aryTableColumns as $strKey => $aryColumn)
        {
            $strType = $aryColumn[self::DB_TYPE];
            $strColumnProps = $strType;
            if(isset($aryColumn[self::DB_SIZE]))
            {
                $strColumnProps .= '(' . $aryColumn[self::DB_SIZE] . ')';
            }
            else
            {
                if($strType == self::DB_TYPE_INT)
                {
                    $intSize = 11;
                }
                elseif($strType == self::DB_TYPE_BIGINT)
                {
                    $intSize = 20;
                }
                elseif($strType == self::DB_TYPE_DOUBLE)
                {
                    $intSize = '10,2';
                }
                elseif($strType == self::DB_TYPE_BOOL)
                {
                    $intSize = 1;
                }
                else
                {
                    $intSize = 255;
                }
                $strColumnProps .= '(' . $intSize . ')';
            }
            if(isset($aryColumn[self::DB_REQUIRED]) && $aryColumn[self::DB_REQUIRED] === true)
            {
                $strColumnProps .= ' NOT NULL';
            }
            else
            {
                $strColumnProps .= ' DEFAULT NULL';
            }

            if(isset($aryColumn[self::DB_AUTO_INCREMENT]) && $aryColumn[self::DB_AUTO_INCREMENT] === true)
            {
                $strColumnProps .= ' AUTO_INCREMENT';
            }

            if(isset($aryColumn[self::DB_UNIQUE]) && $aryColumn[self::DB_UNIQUE] === true)
            {
                $strColumnProps .= ' UNIQUE';
            }

            $aryColumns[] = '`' . $strKey . '` ' . $strColumnProps;

            if(isset($aryColumn[self::DB_PRIMARY]))
            {
                $aryPrimary[] = $strKey;
            }
            if(isset($aryColumn[self::DB_UNIQUE]))
            {
                $aryUnique[] = $strKey;
            }
        }

        $strColumnQuery = implode(', ', $aryColumns);

        if(count($aryPrimary))
        {
            $strColumnQuery .= ', PRIMARY KEY (`' . implode('`, `', $aryPrimary) . '`)';
        }

        $strQuery = 'CREATE TABLE IF NOT EXISTS `' . $this->getTableName() . '` (' . $strColumnQuery . ')';
        $objResult = mysqli_query($this->getConnection(), $strQuery);
        if($objResult == false)
        {
            print_r($this->getConnection()->error);
        }
    }// createTable


    /**
     * Get database connection
     *
     * @since 11. February 2014, v. 1.00
     * @return \mysqli
     */
    private function getConnection()
    {
        return $this->objDb->getConnection();
    }// getConnection


    /**
     * Get fields formatted - for query
     *
     * @param array $aryFields Fields
     *
     * @since 11. February 2014, v. 1.00
     * @return string Fields query formatted
     */
    private function getFieldsFormatted($aryFields)
    {
        return '(`' . implode('`, `', $aryFields) . '`)';
    }// getFieldsFormatted


    private function getValuesFormatted($aryValues)
    {
        return '("' . implode('", "', array_values($aryValues)) . '")';
    }// getValuesFormatted


    /**
     * Get limit formatted - for query
     * @param integer $intStart Start
     * @param integer|null $intLimit Limit
     *
     * @since 11. February 2014, v. 1.00
     * @return string Limit query formatted
     */
    private function getLimitFormatted($intStart, $intLimit = null)
    {
        $strLimit = ' LIMIT ' . $intStart;
        if($intLimit)
        {
            $strLimit .= ',' . $intLimit;
        }
        return $strLimit;
    }// getLimitFormatted


    /**
     * Query
     *
     * @param $strQuery
     *
     * @since 10. March 2014, v. 1.00
     * @return \mysqli
     */
    private function query($strQuery)
    {
        $objConnection = $this->getConnection();
        $objQuery = $objConnection->query($strQuery);

        if($objQuery === false)
        {
            print_r($objConnection->error);
            exit;
        }
        return $objQuery;
    }// query


    /**
     * Select from database
     * @param array $aryFields Fields to select
     * @param string $strWhere Where clause
     * @param string $strOrder Order
     * @param integer|null $intStart Start identifier
     * @param integer|null $intLimit Limit identifier
     *
     * @since 11. February 2014, v. 1.00
     * @return array|mixed
     */
    public function select($aryFields, $strWhere = '', $strOrder = '', $intStart = null, $intLimit = null)
    {
        $aryResult = array();
        $strFields = '`';
        $strFields .= implode('`, `', $aryFields);
        $strFields .= '`';

        if($strWhere && !stristr($strWhere, 'deleted'))
        {
            $strWhere .= ' AND `' . self::COL_DELETED . '`=0';
        }

        if($strWhere)
        {
            $strWhere = ' WHERE ' . $strWhere;
        }

        if($strOrder)
        {
            $strOrder = ' ORDER BY ' . $strOrder;
        }

        $strLimit = '';
        if($intLimit)
        {
            $strLimit = $this->getLimitFormatted($intStart, $intLimit);
        }

        $strSelect = 'SELECT ' . $strFields . ' FROM `' . $this->getTableName() . '` ' . $strWhere . $strOrder . $strLimit;

        $objSelect = $this->query($strSelect);

        while($aryRow = $objSelect->fetch_assoc())
        {
            $aryResult[] = $aryRow;
        }

        return $aryResult;
    }// select


    /**
     * Insert into database
     *
     * @param array $aryValues Values to insert (key => value)
     *
     * @since 11. February 2014, v. 1.00
     * @return integer Insert id
     */
    public function insert($aryValues)
    {
        $this->validateTableColumnContent($aryValues);
        $objConnection = $this->getConnection();

        $strFields = $this->getFieldsFormatted(array_keys($aryValues));
        $strValues = $this->getValuesFormatted($aryValues);
        $strQuery = 'INSERT INTO ' . $this->getTableName() . ' ' . $strFields . ' VALUES ' . $strValues;
        $objInsert = $this->query($strQuery);
        return mysqli_insert_id($objConnection);
    }// insert


    /**
     * Update entry in database
     *
     * @param array $aryValues Values to update (key => value)
     * @param string $strWhere Where clause
     *
     * @since 11. February 2014, v. 1.00
     * @return integer Entry id
     */
    public function update($aryValues, $strWhere)
    {
        $objConnection = $this->getConnection();
        $strValues = '';
        foreach($aryValues as $strKey => $strValue)
        {
            if($strValues) $strValues .= ', ';
            $strValues .= '`' . $strKey . '`="' . $strValue . '"';
        }
        $strQuery = 'UPDATE `' . $this->getTableName() . '` SET ' . $strValues . ' WHERE ' . $strWhere;
        $objUpdate = mysqli_query($objConnection, $strQuery);
        if($objUpdate === false)
        {
            print_r($objConnection->error);
        }
        return mysqli_insert_id($objConnection);
    }// update


    /**
     * Delete query
     *
     * @param string $strWhere Delete where clause
     *
     * @since 12. February 2014, v. 1.00
     * @return boolean True if delete is successful, false if not
     */
    public function delete($strWhere)
    {
        $this->update(array(self::COL_DELETED => time()), $strWhere);
        return true;
        /*$blnSuccess = false;
        $objConnection = $this->getConnection();
        $strQuery = 'DELETE FROM `' . $this->getTableName() . '` WHERE ' . $strWhere;
        $objDelete = mysqli_query($objConnection, $strQuery);
        if($objDelete === false)
        {
            print_r($objConnection->error);
        }
        else $blnSuccess = true;
        return $blnSuccess;*/
    }// delete


    /**
     * Validate table column content (secure that all data provided matches table requirements)
     *
     * @param array $aryContent Content to validate (key => value)
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    private function validateTableColumnContent($aryContent)
    {
        $aryTableColumns = $this->getTableColumns();
        $aryRequired = array();
        $aryRequiredNotSet = array();
        foreach($aryTableColumns as $strKey => $aryParams)
        {
            if(isset($aryParams[self::DB_REQUIRED]) && !isset($aryParams[self::DB_AUTO_INCREMENT]))
            {
                if(!isset($aryContent[$strKey]))
                {
                    $aryRequiredNotSet[] = $strKey;
                }
                $aryRequired[] = $strKey;
            }
        }

        if(count($aryRequiredNotSet))
        {
            echo 'Required fields "' . implode(', ', $aryRequiredNotSet) . '" not set for table "' . $this->getTableName() . '"';
            exit;
        }
    }// validateTableColumnContent


}// DBTable