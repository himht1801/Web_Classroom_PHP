<?php
    function openMySQLConnection()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "myclassroom";
    
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            //echo "Connected failed";
            die("Connection failed: " . $conn->connect_error);
        }
        //echo "Connected successfully";
        return $conn;
    }
    
    
    function closeMySQLConnection($conn)
    {
        $conn->close();
    }
    
?>