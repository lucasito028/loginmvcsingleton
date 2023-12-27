<?php
    
    namespace App\model\server\db;
    abstract class Keys{

        protected String $host = "localhost";
        protected String $user = "root";
        protected String $db = "itospet";
        protected String $password = "";
        protected int $port = 3306;
        
    //The part of Connect
        protected object $conn;
    }