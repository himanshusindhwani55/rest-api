<?php

function connect_db()
{
$server= 'localhost'; // server name
$user = 'root';       // username 
$password = 'admin123';// password
$database = 'slim';   // name of database

// establishing connection to database
$conn =new mysqli($server,$user,$password,$database);

return $conn;
}

?>