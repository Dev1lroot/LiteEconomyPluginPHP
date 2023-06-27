<?php

class Database
{
    private $connect;
    
    public function __construct($connection)
    {
        // Getting connection data from connection URI e.g. mysql://username:password@localhost:3306/database_name
        $this->connect = $this->establishConnection($connection);
    }
    private function establishConnection($connection)
    {
        // Explode connection URI by regular expression
        $matches = [];
        preg_match("/^((?<protocol>[^:@ ]{1,128})(\:\/\/))((?<username>[^:@]{1,128})((\:)(?<password>[^:@ ]{1,128})|)(\@)|)(?<hostname>[^\/:@ ]{1,128})((\:)(?<port>[0-9]{1,5})|)(\/(?<database>[^\/:@ ]{1,128})|)$/",$connection,$matches);
        $matches = (object) $matches;
        
        // Skipping work if something went wrong with the credentials
        if(!isset($matches->protocol)) die("Database: error: no protocol specified");
        if(!isset($matches->hostname)) die("Database: error: no hostname specified");
        if(!isset($matches->username)) $matches->username = "root";
        if(!isset($matches->password)) $matches->password = "";

        // Selecting the database management system to return the connection object
        switch($matches->protocol)
        {
            case "mysql":
                if(!isset($matches->port)) $matches->port = 3306;
                return new mysqli($matches->hostname,$matches->username,$matches->password,$matches->database,intval($matches->port));
                break;
            
            case "mongodb":
                // W.I.P
                if(!isset($matches->port)) $matches->port = 27017;
                die("Database: error: unsupported protocol: " . $matches->protocol);
                break;

            default:
                die("Database: error: unknown protocol: " . $matches->protocol);
                break;
        }
    }
    public function query($query)
    {
        // Executing a query
        return $this->connect->query($query);
    }
    public function rows($query)
    {
        // Getting rows from query
        try{
            return $this->query($query)->fetch_all(MYSQLI_ASSOC);
        }
        catch(Exception $e)
        {
            return [];
        }
    }
    public function row($query)
    {
        // Getting one row as object from query
        try{
            return (object) $this->query($query)->fetch_assoc();
        }
        catch(Exception $e)
        {
            return (object) [];
        }
    }
}

?>
