<?php

class Connection {
    # Constructor
    function __construct()
    {
    }

    # Destructor
    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    # connect
    public function connect(){
        # Database and host values
        $host = "mysql.hostinger.mx";
        $user = "u340525141_bibfr";
        $password = "biblioteca";
        $database = "u340525141_bibfr";

        # Try to connect to the database
        $conn = mysqli_connect($host, $user, $password, $database);

        # Set the charset to UTF-8
        $conn->query("SET NAMES 'utf8");

        # Return the connection to the functions file
        return $conn;
    }

    # close
    public function close(){
        mysql_close();
    }
}