<?php
class LiteEconomy{
    
    private $table;
    private $connection;

    public function __construct($connection,$table = "lite_eco")
    {   
        // Setting up the table we will work with
        $this->table = $table;

        // Creating a connection using my database overlay
        $this->connection = new Database($connection);
    }
    private function selectBalance(MinecraftPlayer $player)
    {
        // Getting all the data about existing account
        return $this->connection->row("SELECT * FROM `{$this->table}` WHERE `uuid` = '{$player->uuid}' LIMIT 1");
    }
    private function updateBalance(MinecraftPlayer $player,$value)
    {
        // Updating database record of the player wealth, it will return true|false on success|failure of the operation
        return $this->connection->query("UPDATE `{$this->table}` SET `money` = {$value} WHERE `uuid` = '{$player->uuid}' LIMIT 1");
    }
    private function createBalance(MinecraftPlayer $player,$value)
    {
        // Inserting new record of the player wealth, it will return true|false on success|failure of the operation
        return $this->connection->query("INSERT INTO `{$this->table}` (`uuid`,`money`) VALUES ('{$player->uuid}','{$value}')");
    }
    public function getBalance(MinecraftPlayer $player)
    {
        // Getting all the data about existing account
        $result = $this->selectBalance($player);

        // Returning only the numeric value, or null if doesn't exists
        return (isset($result->money)) ? floatval($result->money) : 0;
    }
    public function setBalance(MinecraftPlayer $player, $value)
    {
        // Filter input parameters
        $value  = floatval($value);

        // Checking whether is account exists
        $result = $this->selectBalance($player);
        
        if(isset($result->id))
        {
            // Update existing account
            return $this->updateBalance($player,$value);
        }
        else
        {
            // Create new account
            return $this->createBalance($player,$value);
        }
    }
}
?>
