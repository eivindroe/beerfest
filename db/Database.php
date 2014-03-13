<?php
namespace Beerfest;

class Database
{
    /**
     * Database connection
     * @var Database
     */
    private $objConnection;

    /**
     * Constructor
     *
     * @since 11. February 2014, v. 1.00
     */
    public function __construct()
    {
        $this->connect();
    }// __construct


    /**
     * Connect to database and select given database
     *
     * @since 11. February 2014, v. 1.00
     * @return \mysqli
     */
    private function connect()
    {
        $objConfig = new \Beerfest\Config();
        $objConnection = new \mysqli();
        $objConnection->connect($objConfig->getName(), $objConfig->getUsername(), $objConfig->getPassword(), $objConfig->getTableName());
        $this->objConnection = $objConnection;
    }// connect


    /**
     * Disconnect database
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    private function disconnect()
    {
        mysqli_close($this->getConnection());
    }// disconnect


    /**
     * Get database connection
     *
     * @since 11. February 2014, v. 1.00
     * @return \mysqli
     */
    public function getConnection()
    {
        return $this->objConnection;
    }// getConnection


    /**
     * Destruct database object
     *
     * @since 11. February 2014, v. 1.00
     * @return void
     */
    public function __desctruct()
    {
        $this->disconnect();
    }// __desctruct


}// database