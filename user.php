<?php
include "db.php";

class User{
    
    /**
     *  PROPERTIES
     */

    private $id;
    private $username;
    private $pass;
    private $hashpass;
    private $firstname;
    private $lastname;
    private $gender;

    

    /**
     * Set the value of User
     *
     * @return  self
     */ 
    protected function setUser($arg)
    {
        if (isset($arg['id'])) $this->id = $arg['id'];
        if (isset($arg['username'])) $this->username = $arg['username'];
        if (isset($arg['pass']))$this->pass = $arg['pass'];
        if (isset($arg['hashpass']))$this->hashpass = $arg['hashpass'];
        if (isset($arg['firstname']))$this->firstname = $arg['firstname'];
        if (isset($arg['lastname']))$this->lastname = $arg['lastname'];
        if (isset($arg['gender'])) $this->gender = $arg['gender'];

        return $this;
    }

    /**
     *  Get the value of Uservfrom DB
     */
    protected function getUser($user){
        try {
            $sql = "SELECT * FROM Users WHERE username=?";
            $result =  (new DbConn())->sql($sql,[$user]);
            if ($result->rowCount() == 0) throw new Exception("Podany login nie istnieje");
            $result = $result->fetch(PDO::FETCH_ASSOC);
            $this->setUser($result);
        }catch(Exception $e){
            echo  $e->getMessage().'<br>';
            $_SESSION["err"] =$e->getMessage();  
            throw new Exception("Podany login nie istnieje");
        }
    }

    public function registerUser($args){
        $this->validateUser($args);
        try {

            if ($this->pass==null) throw new Exception("Brak wystarczających danych użytkownika");

            $sql = "SELECT id FROM Users WHERE username=?";
            $result =  (new DbConn())->sql($sql,[$this->username]);
            if ($result->rowCount() > 0) throw new Exception("Podany login jest już w użyciu");

            $this->hashpass=password_hash($this->pass,PASSWORD_DEFAULT);
            $sql = "INSERT INTO Users ( username, hashpass, firstname, lastname, gender)
            VALUES (?, ?, ?, ?, ?)";
            $result =  (new DbConn())->sql($sql,[$this->username,$this->hashpass,$this->firstname,$this->lastname,$this->gender]);
            echo 'Zarejestrowano<br>';
        } catch (Exception $e) {
            echo  $e->getMessage().'<br>';
            $_SESSION["err"] =$e->getMessage();
        }
    }

    public function loginUser($user,$pass){
        try {
            $this->getUser($user);
            if (password_verify($pass, $this->hashpass)==false) throw new Exception("Błędny Login lub Hasło");
            $_SESSION['user'] = $this->username;
            $_SESSION['id'] = $this->id;
            $_SESSION['firstname']  =  $this->firstname ;
            $_SESSION['lastname']  =  $this->lastname ;
            $_SESSION['gender']  =  $this->gender  ;
        }catch (Exception $e)
        {
            echo  $e->getMessage().'<br>';
            $_SESSION["err"] =$e->getMessage(); 
        }
    }

    public function logoutUser(){
        if (isset($_SESSION['user'])){
            unset($_SESSION['id']);
            unset($_SESSION['user']);
            unset($_SESSION['firstname']);
            unset($_SESSION['lastname']);
            unset($_SESSION['gender']);
            $_SESSION['logout']=true;
        }
    }

    protected function validateUser($args){
        try {
                
            if (strlen($args["username"]) <6) throw new Exception("Nazwa użytkownika jest za krótka. Minimum 6 znaków alfanumerycznych.");
            if (ctype_alnum($args["username"]) == false ) throw new Exception("Nazwa użytkownika zawiera niedozwolone znakia. Tylko znaki alfanumeryczne."); 
            if (strlen($args["pass"]) <8) throw new Exception("Hasło jest za krótkie. Minimum 8 znaków.");
            if (isset($args["pass2"])) if (strcmp ($args["pass"] , $args["pass2"] )!=0 ) throw new Exception("Hasła są różne. Wprowadź dwa takie same hasła.");
            if (strlen($args["firstname"]) <= 0) throw new Exception("Nie wprowadzono imienia.");
            if (strlen($args["lastname"]) <= 0) throw new Exception("Nie wprowadzono nazwiska.");
            if (strlen($args["gender"]) <= 0) throw new Exception("Nie wprowadzono płci.");

            $this->setUser($args);
            }
        catch(Exception $e){
            echo  $e->getMessage().'<br>';
            $_SESSION["err"] =$e->getMessage();
        }
    }
}

?>