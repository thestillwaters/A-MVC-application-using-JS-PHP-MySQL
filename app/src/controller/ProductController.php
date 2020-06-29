<?php


namespace yh\a3\controller;

use yh\a3\exception\MysqlErrorException;
use yh\a3\exception\EmailTakenException;
use yh\a3\exception\NullResultException;
use yh\a3\model\ProductCollectionModel;
use yh\a3\View\View;
use Exception;

class ProductController extends Controller
{
    /**
     * product index
     */
    public function indexAction()
    {
        self::checkAuthentication();
        try {
            $collection = new \yh\a3\Model\ProductCollectionModel();
            $products = $collection->selecting()->getProducts();
            $view = new View('productIndex');
            echo $view->addData('products', $products)
                ->addData('name', $_SESSION['CustomerName'])
                ->addData('linkTo', function ($route, $params = []) {
                        return $this->linkTo($route, $params);
                })->render();
        } catch (MysqlErrorException $queryException) {
            self::renderException($queryException);
        }
    }

    /**
     * searching , render the search page
     * @throws Exception
     */
    public function searchAction()
    {
        self::checkAuthentication();
        try {
            $collection = new \yh\a3\Model\ProductCollectionModel();
            $products = $collection->selecting()->getProducts();
            $view = new View('productSearching');
            echo $view->addData('products', $products)
                ->addData('linkTo', function ($route, $params = []) {
                    return $this->linkTo($route, $params);
                })->render();
        } catch (MysqlErrorException $queryException) {
            self::renderException($queryException);
        }
    }

    /**
     * after searched , using AJAX to return searching result
     * @throws Exception
     */
    public function afterSearchAction()
    {
        self::checkAuthentication();
        $productName = $_POST['productName'];
        try {
            $collection = new \yh\a3\Model\ProductCollectionModel();
            $products = $collection->searching($productName)->getProducts();
            $view = new \yh\a3\view\View('productSearched');
            echo $view->addData('products', $products)
                ->addData('linkTo', function ($route, $params = []) {
                    return $this->linkTo($route, $params);
                })->render();
        } catch (MysqlErrorException $queryException) {
            self::renderException($queryException);
        } catch (NullResultException $NullResultException) {
            $view = new View('exceptionEmptyResult');
            echo $view->addData('EmailTakenException', $NullResultException)
                ->render();
        }
    }

    /**
     * browsing , display the browsing page
     * @throws Exception
     */
    public function browseAction()
    {
        self::checkAuthentication();
        try {
            $collection = new \yh\a3\Model\ProductCollectionModel();
            $products = $collection->selecting()->getProducts();

            $view = new View('productBrowsing');
            echo $view->addData('products', $products)
                ->addData('linkTo', function ($route, $params = []) {
                    return $this->linkTo($route, $params);
                })->render();
        } catch (MysqlErrorException $queryException) {
            self::renderException($queryException);
        }
    }

    /**
     * show browse result , keep update checkbox
     * @throws Exception
     */
    public function browsedAction()
    {
        self::checkAuthentication();
        $queryArray = array(
            "isInStock" => -1,
            "hammersID" => 0,
            "heatGunsID" => 0,
            "pliersID" => 0,
            "screwdriversID" => 0
        );
        if (isset($_POST['isInStock'])) {
            $queryArray['isInStock'] = $_POST['isInStock'];
        }
        if (isset($_POST['heatGunsID'])) {
            $queryArray['heatGunsID'] = $_POST['heatGunsID'];
        }
        if (isset($_POST['screwdriversID'])) {
            $queryArray['screwdriversID'] = $_POST['screwdriversID'];
        }
        if (isset($_POST['pliersID'])) {
            $queryArray['pliersID'] = $_POST['pliersID'];
        }
        if (isset($_POST['hammersID'])) {
            $queryArray['hammersID'] = $_POST['hammersID'];
        }
        try {
            $collection = new \yh\a3\Model\ProductCollectionModel();
            $products = $collection->browsing($queryArray)->getProducts();
            $view = new View('productSearched');
            echo $view->addData('products', $products)
                ->addData('linkTo', function ($route, $params = []) {
                    return $this->linkTo($route, $params);
                })->render();
        } catch (MysqlErrorException $queryException) {
            self::renderException($queryException);
        } catch (EmailTakenException $EmailTakenException) {
            $view = new View('exceptionEmptyResult');
            echo $view->addData('EmailTakenException', $EmailTakenException)
                ->render();
        }
    }
}
