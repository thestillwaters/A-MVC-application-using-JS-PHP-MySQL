<?php


namespace yh\a3\model;

use yh\a3\exception\MysqlErrorException;
use mysqli;

class Model
{
    protected $db;

    /**
     * Model constructor.
     * @throws MysqlErrorException
     *
     */
    public function __construct()
    {
        $envs = getenv();
        $dbhost = $envs['MYSQL_HOST'];
        $dbName = $envs['MYSQL_DATABASE'];
        $dbUser = $envs['MYSQL_USER'];
        $dbPass = $envs['MYSQL_PASSWORD'];
        $this->db = new mysqli(
            $dbhost,
            $dbUser,
            $dbPass
        );
        /** can't connect to MYSQL*/
        if (!$this->db) {
            throw new \yh\a3\Exception\MysqlErrorException($this->db->connect_error, $this->db->connect_errno);
        }
        $this->db->query("CREATE DATABASE IF NOT EXISTS $dbName ");
        if (!$this->db->select_db($dbName)) {
            throw new \yh\a3\Exception\MysqlErrorException("Failed selecting Database");
        }
        $isTableExist = $this->db->query("SHOW TABLES LIKE 'Customer';");
        if ($isTableExist->num_rows == 0) {
            /**table doesn't exist
            create it and populate with sample data*/
            $create = $this->db->query(
                "CREATE TABLE`Customer` (
                              `CustomerName` VARCHAR (40) NOT NULL ,
                              `Email` VARCHAR (40) NOT NULL UNIQUE,
                              `Username` VARCHAR (40) NOT NULL UNIQUE ,
                              `Password` VARCHAR (40) NOT NULL,
                              `CustomerID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                              PRIMARY KEY (`customerID`)
                               );"
            );

            if (!$create) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed creating table Customer");
            }
            /**
             * create sample data
             */
            $insert = $this->db->query(
                "INSERT INTO`Customer`
                                VALUES ('Tim Taylor','ttaylor@163.com','TheToolman','TheToolman',NULL);"
            );
            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed inserting TheToolman");
            }
        }
        $isTableExist = $this->db->query("SHOW TABLES LIKE 'ProductCategory';");
        if ($isTableExist->num_rows == 0) {
            /**table doesn't exist
            create it and populate with sample data*/
            $create = $this->db->query(
                "CREATE TABLE`ProductCategory` (
                              `CategoryID` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
                              `Category` VARCHAR(20) NOT NULL,
                              PRIMARY KEY (`CategoryID`)  );"
            );

            if (!$create) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed creating table ProductCategory");
            }
            /**create 4 categories*/
            $insert = $this->db->query(
                "INSERT INTO`ProductCategory`
                                VALUES ('1','Hammers');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert Hammers category");
            }

            $result = $this->db->query(
                "INSERT INTO`ProductCategory`
                                VALUES ('2','Heat guns');"
            );

            if (!$result) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert Hammers category");
            }

            $insert = $this->db->query(
                "INSERT INTO`ProductCategory`
                                VALUES ('3','Pliers');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert Pliers category");
            }

            $insert = $this->db->query(
                "INSERT INTO`ProductCategory`
                                VALUES ('4','Screwdrivers');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert Screwdrivers category");
            }
        }


        $isTableExist = $this->db->query("SHOW TABLES LIKE 'Product';");

        if ($isTableExist->num_rows == 0) {
            /**table doesn't exist
            create it and populate with sample data*/
            $create = $this->db->query(
                "CREATE TABLE`Product` (
                          `ProductID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                          `SKU` VARCHAR(20) NOT NULL UNIQUE, 
                          `StockQty` INT UNSIGNED,
                          `Price` DECIMAL(10,2) UNSIGNED NOT NULL ,
                          `ProductName` VARCHAR(40) NOT NULL UNIQUE ,
                          `CategoryID` SMALLINT UNSIGNED NOT NULL ,
                          PRIMARY KEY (`ProductID`),
                          FOREIGN KEY (`CategoryID`) REFERENCES `ProductCategory`(`CategoryID`) ON DELETE CASCADE );"
            );

            if (!$create) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed creating table Product");
            }
            /**parameters are(SKU, StockQty, Cost, Name, CategoryID), '1' for 'Hammers*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'ham11','11','39.95','Claw Hammer','1');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed inserting table Product Claw Hammer");
            }
            /**parameters are(SKU, StockQty, Cost, Name, CategoryID), '1' for 'Hammers*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'ham22','2','66.00','Sledge Hammer','1');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert Product Sledge Hammer");
            }
            /**parameters are(SKU, StockQty, Cost, Name, CategoryID), '1' for 'Hammers*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'ham23','7','24.99','Soft-face Hammer','1');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert  Soft-face Hammer");
            }
            /** parameters are(SKU, StockQty, Cost, Name, CategoryID), 2 for heat gun*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'hg1','0','55.00','Big heat gun','2');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert  Bosch heat gun");
            }
            /** parameters are(SKU, StockQty, Cost, Name, CategoryID), 4 for Screwdriver*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'screw03','17','11.95','Square Screwdriver','4');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert  flat Screwdrivers");
            }
            /** parameters are(SKU, StockQty, Cost, Name, CategoryID), 4 for Screwdriver*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'screw23','30','11.95','Phillips Screwdrivers','4');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert Phillips Screwdrivers");
            }
            /** parameters are(SKU, StockQty, Cost, Name, CategoryID), 3 for pliers*/
            $insert = $this->db->query(
                "INSERT INTO`Product`
                                VALUES (NULL,'Pliers2','15','32.00','Iron pliers','3');"
            );

            if (!$insert) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert  Circlip pliers set 4pc");
            }
        }
    }
}
