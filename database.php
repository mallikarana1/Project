<?php

//Check connection
function connectDatabase($dbHost, $dbUname, $dbPassword,$dbname)
{
    $mysqli = null;
    try {
        $mysqli = new mysqli($dbHost, $dbUname, $dbPassword,$dbname);
        if ($mysqli->connect_errno) {
            die("Connection failed:" . $mysqli->connect_errno);
        }
        return $mysqli;
    } catch (Exception $th) {
        return null;
    }
}

function createDatabaseAndTable(){
    $dbHost="localhost";
    $dbUname="root";
    $dbPassword="";
    $dbName = "medicineDatabase"; 

    $mysqli = connectDatabase($dbHost, $dbUname, $dbPassword, $dbName);

    try{
        //Create database
        $sqlCreateDatabase="CREATE DATABASE IF NOT EXISTS $dbName";
        if (!mysqli_query($mysqli,$sqlCreateDatabase)){
            throw new Exception("Error creating database:".mysqli_error($mysqli));
        }
        echo "Database '$dbName' created successfully.\n ";
        
        //Create the medicine table
        $sqlCreateTable="CREATE TABLE IF NOT EXISTS medicine (
            id BINARY(16) PRIMARY KEY DEFAULT (UUID_TO_BIN(UUID())),
            SNo INT(15) UNSIGNED AUTO_INCREMENT,
            Medicinename VARCHAR(100),
            Manufacturer VARCHAR(100),
            DateOfExpiry Date,
            Class INT(20) ,
            UNIQUE KEY unique_sno (SNo)
        )";
        if(!mysqli_query($mysqli,$sqlCreateTable)){
            throw new Exception("Error creating table :".mysqli_error($mysqli));
        }
        $dbname=$dbName;
        echo "Table 'medicine' created successfully.\n";
        return $dbname;
    }catch(Exception $th){
        echo "Error: " .$th->getMessage();
        return null;
    }finally{
       // mysqli_close($mysqli);
    }
}

$database=createDatabaseAndTable();

if ($database) {
    $mysqli = connectDatabase("localhost", "root", "", $database); 

    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $dateOfManufacture = mysqli_real_escape_string($mysqli, $_POST['dateOfManufacture']);
    $dateOfExpiry = mysqli_real_escape_string($mysqli, $_POST['dateOfExpiry']);
    $class = mysqli_real_escape_string($mysqli, $_POST['Number']);

    $sql = "INSERT INTO medicine (Medicinename, Manufacturer, DateOfExpiry, Class) VALUES ('$name','$dateOfManufacture','$dateOfExpiry','$class')";

    if ($mysqli->query($sql) === TRUE) {
        echo "Record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
    }

    mysqli_close($mysqli);
}


?>
