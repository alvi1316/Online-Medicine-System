<?php

//This class will handle all the db quary
class dbmanager{

    //This function creates connections and returns the connection
    function dbConnection(){
        $conn = new mysqli("localhost", "root", "", "medic");
        if ($conn->connect_error) {
            die("Connection Error" . $conn->connect_error);
        } else {
            return $conn;
        }
    }

    function selectAll($con){
        $qry = "SELECT * FROM product";
        $result = $con->query($qry);
        $row = $result->fetch_all();
        return $row;
    }

    function search($con,$name){
        $qry = "SELECT * FROM product WHERE product_name LIKE '%$name%'";
        $result = $con->query($qry);
        $row = $result->fetch_all();
        return $row;
    }

    function insertOrder($con,$productName,$customerName,$productQuantity,$totalPrice,$address,$fileName){
        $qry = "INSERT INTO orderdetails( product_name, customer_name, product_quantity, total_price, delivery_address,file_name) 
        VALUES('$productName','$customerName','$productQuantity',$totalPrice,'$address','$fileName')";
        $con->query($qry);
    }
}
?>