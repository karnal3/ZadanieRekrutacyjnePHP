<?php

class DbConn {

    /**
     *  PROPERTIES
     */

    private const SERVERNAME = "localhost";
    private const USERNAME = "root";
    private const PASSWORD = "";
    private const DBNAME = "UsersDB";
    private $conn;

    /**
     *  CONSTRUCT AND DECONSTRUCT
     */

     function __construct(){
         try {
            $this->setConnWithTable(true);
         }catch(PDOException $e){
            echo "Connection failed:" . $e->getMessage().'<br>';
            try {
                $this->setConnWithTable(false);
                // sql to create datebase
                $sql = "CREATE DATABASE ".DbConn::DBNAME;
                $this->getConn()->exec($sql);
                echo "Database created successfully<br>";
                sleep(2);
                //sql to create table
                $sql = "CREATE TABLE Users (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(30) NOT NULL,
                    hashpass VARCHAR(255) NOT NULL,
                    firstname VARCHAR(30) NOT NULL,
                    lastname VARCHAR(30) NOT NULL,
                    gender VARCHAR(6) NOT NULL
                    )";
                 // use exec() because no results are returned
                 $this->setConnWithTable(true);
                 $this->getConn()->exec($sql);
                 echo "Table Users created successfully";
                 sleep(1);
                 //sql to create table
                $sql = "CREATE TABLE Docs (
                        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        iduser INT(6) UNSIGNED NOT NULL,
                        FOREIGN KEY (iduser) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE,
                        vactype VARCHAR(60) NOT NULL,
                        datefrom DATE NOT NULL,
                        dateto DATE NOT NULL,
                        dateset DATE NOT NULL,
                        fileurl VARCHAR(255) NOT NULL,
                        comment VARCHAR(255) NULL
                        )";
                 // use exec() because no results are returned
                 $this->getConn()->exec($sql);
                 echo "Table Docs created successfully";
                 sleep(1);
            }catch(PDOException $e)
            {
                echo $sql . "<br>" . $e->getMessage().'<br>';
            }
         }
     }

     function __destruct(){
        if (isset($this->conn)) $this->conn = null;
     }
    /**
     * GETTERS AND SETTERS
     */

    /**
     * Get the value of conn
     */ 
    public function getConn(){
        return $this->conn;
    }

    /**
     * Set the value of conn
     *
     * @return  self
     */ 
    protected function setConn($conn){
        $this->conn = $conn;

        return $this;
    }

    /**
     *  METHODS
     */

     public function sql($sql,$arg){
         try{
            $stmt = $this->getConn()->prepare($sql);
            $stmt->execute($arg);
            return $stmt;
            //echo "Execution successfully".'<br>';
         }catch (PDOException $e){
            echo "Transaction failed: " . $e->getMessage().'<br>';
         }
     }
     protected function setConnWithTable($bool){
        try {
            if($bool) $s = ";dbname=".DbConn::DBNAME;
            else $s='';
            $this->setConn(new PDO("mysql:host=".DbConn::SERVERNAME.$s, DBConn::USERNAME, DbConn::PASSWORD));
            $this->getConn()->setAttribute(PDO::ATTR_TIMEOUT, 12);
            $this->getConn()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           // echo "Connected successfully".'<br>';

        }catch (PDOException $e){
            echo "Connection failed: " . $e->getMessage().'<br>';
            throw new PDOException($e->getMessage());
        }
    }
}

?>