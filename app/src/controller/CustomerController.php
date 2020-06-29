<?php



namespace yh\a3\controller;

use yh\a3\exception\MysqlErrorException;
use yh\a3\exception\EmailTakenException;
use yh\a3\exception\UsernameNotFoundException;
use yh\a3\exception\UsernameTakenException;
use yh\a3\exception\UsernamePasswordMismatchException;
use yh\a3\Model\customermodel;
use yh\a3\View\View;

class CustomerController extends Controller
{
    /**
     * Customer index
     */
    public function indexAction()
    {
        $view = new \yh\a3\view\View('userIndex');
        echo $view->addData('linkTo', function ($route, $params = []) {
                    return $this->linkTo($route, $params);
        })->render();
    }
    /**
     * Login
     */
    public function loginValidatingAction()
    {
        $username = $_POST["username"];
        $password = $_POST["password"];
        try {
            $user = new \yh\a3\Model\CustomerModel($username, $password);
            $user->checkMatching();
            $pass = array('statusCode' => true);
            self::printJson($pass);
        } catch (UsernamePasswordMismatchException $notMatchException) {
            $exception = array('statusCode' => false,'exception' => (string)$notMatchException);
            self::printJson($exception);
        } catch (MysqlErrorException $e) {
            self::renderException($e);
        } catch (UsernameNotFoundException $e) {
            $exception = array('statusCode' => false,'exception' => (string)$e);
            self::printJson($exception);
        }
    }
    /**
     * New customer register to check if the input follows the rules
     */
    public function registerValidatingAction()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        /**for model querying database in CustomerModel.php*/
        try {
            $user = new \yh\a3\Model\CustomerModel($username, $password, $name, $email);
            $user->checkExistence();
            self::printJson(array('statusCode' => true));
        } catch (EmailTakenException $EmailTakenException) {
            self::printJson(array('statusCode' => false,'exception' => (string)$EmailTakenException));
        } catch (UsernameTakenException $UsernameTakenException) {
            self::printJson(array('statusCode' => false,'exception' => (string)$UsernameTakenException));
        } catch (MysqlErrorException $MysqlErrorException) {
            self::renderException($MysqlErrorException);
        }
    }
    /**
     * logout
     */
    public function logoutAction()
    {
        session_unset();
        session_destroy();
        header("Location: " . self::linkTo('userIndex'));
        die();
    }
    /**
     *  register
     */
    public function registeringAction()
    {
        $view = new \yh\a3\view\View('registering');
        echo $view->addData('linkTo', function ($route, $params = []) {
            return $this->linkTo($route, $params);
        })->render();
    }

    /**
     *  register completed
     */
    public function registeredAction()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        try {
            $customer = new \yh\a3\Model\CustomerModel($username, $password, $name, $email);
            $customer->save();
            $view = new View('registered');
            echo $view->addData('customer', $customer)
                ->addData('linkTo', function ($route, $params = []) {
                    return $this->linkTo($route, $params);
                })->render();
        } catch (MysqlErrorException $MysqlErrorException) {
            self::renderException($MysqlErrorException);
        }
    }
}
