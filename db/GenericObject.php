<?php
namespace Beerfest;

use Beerfest\DBTable;

class GenericObject
{
    /**
     * Object data
     * @var array
     */
    private $aryData = array();

    /**
     * Database object
     * @var object
     */
    private $objDb;

    /**
     * Object id
     * @var mixed
     */
    private $mxdId;


    /**
     * Constructor
     * @param DBTable $objDb Database table object
     * @param mixed $mxdId Id
     *
     * @since 11. February 2014, v. 1.00
     */
    public function __construct(DBTable $objDb, $mxdId = null)
    {
        if(is_object($objDb))
        {
            $this->objDb = $objDb;
        }
        else
        {
            if(!class_exists($objDb))
            {
                echo 'Class "' . $objDb .'" does not exist';
            }
            $this->objDb = new $objDb();
        }

        if($mxdId)
        {
            if(is_numeric($mxdId))
            {
                $mxdId = \Beerfest\Core\Crypt::encrypt($mxdId);
            }
            $this->load($mxdId);
        }
    }// __construct


    /**
     * Get database object
     *
     * @since 11. February 2014, v. 1.00
     * @return \Beerfest\DBTable
     */
    private function getDbObject()
    {
        return $this->objDb;
    }// getDbObject


    /**
     * Load data from database object
     *
     * @param mixed $mxdId Object id
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    private function load($mxdId)
    {
        $this->mxdId = $mxdId;
        $aryRawData = $this->getDbObject()->select(array_keys($this->getDbObject()->getTableColumns()),
            crypt_sql($this->getTablePrimaryColumn(), $mxdId), '', 0, 1);

        if($aryRawData)
        {
            $this->aryData = $aryRawData[0];
        }
    }// load


    /**
     * Get primary table column
     *
     * @since 20. February 2014, v. 1.00
     * @return string Primary table column name
     */
    private function getTablePrimaryColumn()
    {
        $aryColumns = $this->getDbObject()->getTableColumns();
        $strPrimaryKey = '';
        foreach($aryColumns as $strKey => $aryProperties)
        {
            if(isset($aryProperties[DBTable::DB_PRIMARY]) && $aryProperties[DBTable::DB_PRIMARY] === true)
            {
                $strPrimaryKey = $strKey;
                break;
            }
        }
        return $strPrimaryKey;
    }// getTablePrimaryColumn


    /**
     * Get object id
     *
     * @since 11. February 2014, v. 1.00
     * @return mixed|null Object id if set, null if not
     */
    public function getId()
    {
        return $this->get($this->getTablePrimaryColumn());
    }// getId


    /**
     * Get crypted object id
     *
     * @since 11. February 2014, v. 1.00
     * @return string|null Object id if set, null if not
     */
    public function getCryptId()
    {
        $mxdId = $this->get($this->getTablePrimaryColumn());
        if($mxdId)
        {
            $mxdId = \Beerfest\Core\Crypt::encrypt($mxdId);
        }
        return $mxdId;
    }// getCryptId


    /**
     * Set value
     *
     * @param string $strKey Column key
     * @param string $strValue Column value
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    public function set($strKey, $strValue)
    {
        $aryColumns = $this->objDb->getTableColumns();
        if(!isset($aryColumns[$strKey]))
        {
            echo 'Key "' . $strKey . '" is not valid for table "' . $this->objDb->getTableName() . '"';
        }
        $this->aryData[$strKey] = $strValue;
    }// set


    /**
     * Get object value
     *
     * @param string $strKey Column key
     *
     * @since 11. February 2014, v. 1.00
     * @return string|boolean String value if exists, false if not
     */
    public function get($strKey)
    {
        $mxdReturn = false;
        if(isset($this->aryData[$strKey]))
        {
            $mxdReturn = $this->aryData[$strKey];
        }
        return $mxdReturn;
    }// get


    /**
     * Get all data
     *
     * @since 11. February 2014, v. 1.00
     * @return array Object data
     */
    public function getAll()
    {
        $aryData = $this->aryData;
        $aryData['crypt_id'] = \Beerfest\Core\Crypt::encrypt($this->getId());
        return $this->aryData;
    }// getAll


    /**
     * Delete element
     *
     * @since 12. February 2014, v. 1.00
     * @return boolean True if delete is successful, false if not
     */
    public function delete()
    {
        $objDb = $this->getDbObject();
        return $objDb->delete(sql_where($this->getTablePrimaryColumn(), $this->getId()));
    }// delete


    /**
     * Save object data to database
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    public function save()
    {
        $objDb = $this->getDbObject();
        $aryData = $this->getAll();
        if($this->getId())
        {
            $intId = $objDb->update($aryData, sql_where($this->getTablePrimaryColumn(), $this->getId()));
        }
        else
        {
            $intId = $objDb->insert($aryData);
            $this->set($this->getTablePrimaryColumn(), $intId);
        }

        return $intId;
    }// save


}// GenericObject