<?php

//Check connection
function connectDatabase($dbHost,$dbUname,$dbPassword){
    $mysqli=null;
    try{
        $mysqli=new mysqli($dbHost,$dbUname,$dbPassword);
        if($mysqli->connect_errno){
            die("Connection failed:".$mysqli->connect_errno);
        }
        return $mysqli;
    }catch(Exception $th){
        return null;
    }
}

function createDatabaseAndTable(){
    $dbHost="localhost";
    $dbUname="root";
    $dbPassword="";

    $mysqli=connectDatabase($dbHost,$dbUname,$dbPassword);

    try{
        //Create database
        $sqlCreateDatabase="CREATE DATABASE IF NOT EXISTS medicineDatabase";
        if (!mysqli_query($mysqli,$sqlCreateDatabase)){
            throw new Exception("Error creating database:".mysqli_error($mysqli));
        }
        echo "Database 'medicineDatabase' created successfully.\n ";
        
        //Select the database
        mysqli_select_db($mysqli,"medicineDatabase");
        
        //Create the medicine table
        $sqlCreateTable="CREATE TABLE IF NOT EXISTS medicine (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            SNo INT(15),
            Medicinename VARCHAR(100),
            Manufacturer VARCHAR(100),
            DateOfExpiry Date,
            Class INT(20) 
        )";
        if(!mysqli_query($mysqli,$sqlCreateTable)){
            throw new Exception("Error creating table :".mysqli_error($mysqli));
        }
        $dbname="medicineDatabase";
        echo "Table 'medicine' created successfully.\n";
        return $dbname;
    }catch(Exception $th){
        echo "Error: " .$th->getMessage();
    }finally{
        mysqli_close($mysqli);
    }
}

$database=createDatabaseAndTable();

?>