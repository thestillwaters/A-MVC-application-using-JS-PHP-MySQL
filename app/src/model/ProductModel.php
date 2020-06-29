<?php



namespace yh\a3\model;

use yh\a3\exception\MysqlErrorException;

class ProductModel extends Model
{
    /**
     * @var int Transaction ID
     */
    private $current_productID;
    /**
     * @var string Product name
     */
    private $current_productName;
    /**
     * @var float The amount of transaction
     */
    private $current_price;
    /**
     * @var int The type ID of transaction
     */
    private $current_categoryID;
    /**
     * @var string The type of transaction
     */
    private $current_category;
    /*
     * @var int Account ID that makes this transaction
     */
    private $current_stockQty;
    /**
     * @var string Stock keeping unit
     */
    private $current_sKU;

    /**
     * @return int Transaction ID
     */
    public function getProductID()
    {
        return $this->current_productID;
    }

    /**
     * @return string Product name
     */
    public function getProductName()
    {
        return $this->current_productName;
    }

    /**
     * @return string Stock Keeping Unit
     */
    public function getSKU()
    {
        return $this->current_sKU;
    }

    /**
     * @return float The amount of transaction
     */
    public function getPrice()
    {
        return $this->current_price;
    }

    /**
     * @return string The type ID of transaction
     */
    public function getCategoryID()
    {
        return $this->current_categoryID;
    }

    /**
     * @return int Account ID
     */
    public function getStockQty()
    {
        return $this->current_stockQty;
    }

    /**
     * @return string The type of transaction
     */
    public function getCategory()
    {
        return $this->current_category;
    }

    /**
     *  Loads transaction information from the database
     * @param int $id Transaction ID
     * @return $this ProductModel
     * @throws MysqlErrorException
     */
    public function loading($id)
    {
        if (!$result = $this->db->query("SELECT `ProductID`,`Product`.`ProductID`,`Category`,`SKU`,`StockQty`,`Price`,`ProductName` 
            FROM`Product` JOIN `ProductCategory`ON`Product`.`CategoryID`= `ProductCategory`.`CategoryID`
            WHERE`ProductID` = $id;")
        ) {
            throw new \yh\a3\Exception\MysqlErrorException("Fail loading products from database");
        }
        $result = $result->fetch_assoc();
        $this->current_productName = $result['ProductName'];
        $this->current_category = $result['Category'];
        $this->current_price = $result['Price'];
        $this->current_stockQty = $result['StockQty'];
        $this->current_sKU = $result['SKU'];
        $this->current_productID = $id;

        return $this;
    }
}
