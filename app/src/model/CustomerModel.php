<?php



namespace yh\a3\model;

use yh\a3\exception\MysqlErrorException;
use yh\a3\exception\EmailTakenException;
use yh\a3\exception\UsernameNotFoundException;
use yh\a3\exception\UsernameTakenException;
use yh\a3\exception\UsernamePasswordMismatchException;

class CustomerModel extends Model
{
    /**
     * @var string Username
     */
    private $username;
    /**
     * @var string Password
     */
    private $password;
    /**
     * @var int CustomerID automatically assigned
     */
    private $customerID;
    /**
     * @var string Customer's name
     */
    private $Name;
    /**
     * @var string Customer's E-mail
     */
    private $Email;

    /**
     * CustomerModel constructor.
     * @param string $username
     * @param string $password
     * @param string $name
     * @param string $email
     * @throws MysqlErrorException
     */
    public function __construct($username, $password, $name = null, $email = null)
    {
        parent::__construct();
        $this->username = mysqli_real_escape_string($this->db, $username);
        $this->password = mysqli_real_escape_string($this->db, $password);
        $this->Name = mysqli_real_escape_string($this->db, $name);
        $this->Email = mysqli_real_escape_string($this->db, $email);
    }
    /**
     * @return string Username
     */
    public function getUsername()
    {
        return $this->username;
    }
    /**
     * @return string Password
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * @return string Name
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * @return string Email
     */
    public function getEmail()
    {
        return $this->Email;
    }
    /**
     * @return int UserID
     */
    public function getCustomerID()
    {
        return $this->customerID;
    }

    /**
     * Save register information to database
     * @throws MysqlErrorException
     */
    public function save()
    {
        if (!isset($this->customerID)) {
            $q = "INSERT INTO`Customer`VALUES('$this->Name','$this->Email','$this->username','$this->password',NULL);";
            if (!$result = $this->db->query($q)) {
                throw new \yh\a3\Exception\MysqlErrorException("Failed to insert  customer information");
            }
            $this->customerID = $this->db->insert_id;
        }
    }

    /**
     * Check the database whether username password  matches
     * @throws UsernamePasswordMismatchException
     * @throws UsernameNotFoundException
     */
    public function checkMatching()
    {
        if (!empty($this->username) && !empty($this->password)) {
            if (!empty($this->username)) {
                $sql = "SELECT * FROM`Customer` WHERE `Username`='$this->username'";
                $result = mysqli_query($this->db, $sql);
                $count = mysqli_num_rows($result);
                if (!$count == 1) {
                    throw new \yh\a3\Exception\UsernameNotFoundException();
                }
            }
            $sql = "SELECT * FROM`Customer` 
                                    WHERE `Username`='$this->username'
                                    AND `Password`='$this->password';";
            $result = mysqli_query($this->db, $sql);
            $row = mysqli_fetch_array($result);
            $count = mysqli_num_rows($result);

            if ($count == 1) {
                $_SESSION['CustomerID'] = $row['CustomerID'];
                $_SESSION['Username'] = $row['Username'];
                $_SESSION['CustomerName'] = $row['CustomerName'];
            } else {
                throw new \yh\a3\Exception\UsernamePasswordMismatchException("Username or Password does not match");
            }
        }
    }

    /**
     * Check the existence of username as well as email
     * @throws MysqlErrorException
     * @throws EmailTakenException
     * @throws UsernameTakenException
     */
    public function checkExistence()
    {
        /**check whether the username exist or not by querying database*/
        if (!$result = $this->db ->query("SELECT * FROM`Customer`WHERE `Username`='$this->username'; ")) {
            throw new MysqlErrorException("Failed to select Customer table customer information");
        }
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            throw new UsernameTakenException("Sorry, this username is occupied!");
        }
        /**check whether the email exist or not by querying database*/
        if (!$result = $this->db ->query("SELECT * FROM`Customer`WHERE `Email`='$this->Email'; ")) {
            throw new MysqlErrorException("Failed to select Customer table customer information");
        }
        $count = mysqli_num_rows($result);
        if ($count == 1) {
            throw new EmailTakenException("Sorry, this Email address has been occupied!");
        }
    }
}
