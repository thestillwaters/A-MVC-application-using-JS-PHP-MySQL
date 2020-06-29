<?php


namespace yh\a3\controller;

use yh\a3\exception\MysqlErrorException;
use yh\a3\View\View;

class Controller
{
    /**
     * Generate a link URL for a named route
     * @param string $route Named route to generate the link URL for
     * @param array $params Any parameters required for the route
     * @return string  URL for the route
     * @throws \Exception
     */
    public static function linkTo($route, $params = [])
    {
        global $router;
        return $router->generate($route, $params);
    }

    /**
     * Create a function to deal with exception
     * class MysqlErrorException extends Exception
     * @param MysqlErrorException $e
     */
    public static function renderException(MysqlErrorException $e)
    {
        //class View from view.php, return string The rendered template.
        $view = new \yh\a3\view\View('exceptionDatabase');
        echo $view->addData('MysqlErrorException', $e)
            ->render();
        die();
    }
    /**
     * call exception function
     * @param string $message error message to print
     * @param string $formerPage string for ”Return“ button to redirect page.
     *
     */

    protected function toExcept($message, $formerPage = "userIndex")
    {
        $view = new \yh\a3\view\View('Exception');
        echo $view->addData("message", $message)
            ->addData("formerPage", $formerPage)
            ->addData('linkTo', function ($route, $params = []) {
                return $this->linkTo($route, $params);
            })->render();
        die();
    }
    /**
     * call json encode function
     * @param array $message error message to print
     *
     */
    protected function printJson($message)
    {
        echo json_encode($message);
    }

    /**
     * Check both authentication and activity of user
     * @throws \Exception
     */

    public static function checkAuthentication()
    {
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
            /**       last request was more than 60 minutes ago */
            session_unset();
            session_destroy();
        }
        /** renew time stamp **/
        $_SESSION['LAST_ACTIVITY'] = time();

        if (!isset($_SESSION['CustomerID'])) {
            header("Location: " . self::linkTo('userIndex'));
            die();
        }
    }
}
