<?php



namespace yh\a3\model;

use yh\a3\exception\MysqlErrorException;
use yh\a3\exception\EmailTakenException;
use yh\a3\exception\NullResultException;

class ProductCollectionModel extends Model
{
    /**
     * @var int[] The collection of product IDs
     */
    private $current_productID;
    /**
     * @var int The number of columns
     */
    private $current_N;

    /**
     * Selecting all the products from Product table
     * @return $this
     * @throws MysqlErrorException
     */
    public function selecting()
    {
        if (!$result = $this->db->query("SELECT `ProductID` FROM `Product`;")) {
            throw new \yh\a3\Exception\MysqlErrorException("Failed to select all the products from database");
        }
        $this->current_productID = array_column($result->fetch_all(), 0);
        $this->current_N = $result->num_rows;

        return $this;
    }

    /**
     * Searching the product by name
     * @param string $query The query of product name
     * @return $this
     * @throws MysqlErrorException
     * @throws NullResultException
     */
    public function searching($query)
    {
        $_query = mysqli_real_escape_string($this->db, $query);
        if (!$result = $this->db
            ->query("SELECT `ProductID`FROM`Product`WHERE `Product`.`ProductName`LIKE '%$_query%';")
        ) {
            throw new \yh\a3\Exception\MysqlErrorException("Search product by partial name failed");
        }
        $this->current_productID = array_column($result->fetch_all(), 0);
        $this->current_N = $result->num_rows;

        if ($this->current_N == 0) {
            throw new \yh\a3\Exception\NullResultException("No result found, please request again.");
        }

        return $this;
    }

    /**
     * Fetch the data user wants to browse from database with query
     * @param $queryArray
     * @return $this
     * @throws MysqlErrorException
     * @throws EmailTakenException
     */
    public function browsing($queryArray)
    {
        $_isInStock = $queryArray['isInStock'];
        $_hammersID = $queryArray['hammersID'];
        $_heatGunsID = $queryArray['heatGunsID'];
        $_pliersID = $queryArray['pliersID'];
        $_screwdriversID = $queryArray['screwdriversID'];
        if (!$result = $this->db->query("SELECT `ProductID` 
                                        FROM`Product`
                                        WHERE (`StockQty` > '$_isInStock' AND `Product`.`CategoryID` = '$_hammersID')
                                        OR (`StockQty` > '$_isInStock' AND `Product`.`CategoryID` = '$_heatGunsID')
                                        OR (`StockQty` > '$_isInStock' AND `Product`.`CategoryID` = '$_pliersID')
                                        OR (`StockQty` > '$_isInStock' 
                                        AND `Product`.`CategoryID` = '$_screwdriversID');")
        ) {
            throw new \yh\a3\Exception\MysqlErrorException("Browse product by category failed");
        }
        $this->current_productID = array_column($result->fetch_all(), 0);
        $this->current_N = $result->num_rows;
        if ($this->current_N == 0) {
            throw new \yh\a3\Exception\EmailTakenException("No result found");
        }
        return $this;
    }
    /**
     * @return \Generator
     *
     */
    public function getProducts()
    {
        foreach ($this->current_productID as $id) {
            // Use a generator to save on memory/resources
            // load accounts from DB one at a time only when required
            try {
                yield (new \yh\a3\Model\ProductModel())->loading($id);
            } catch (MysqlErrorException $queryException) {
                error_log($queryException);
            }
        }
    }
}
