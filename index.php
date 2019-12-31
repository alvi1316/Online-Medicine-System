<?php
    session_start();
    include 'dbmanager.php';
    $db = new dbmanager();
    $con=$db->dbConnection();
    $row = $db->selectAll($con);
    $bool = true;
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
        $bool = false;
    }
    if(isset($_GET["productName"]) && $_GET["quantity"] != 0){
        $temp['productName'] = $_GET["productName"];
        $temp['quantity'] = $_GET["quantity"];
        $temp['price'] = $_GET["price"];
        $index = array_search($temp['productName'], array_column($_SESSION['cart'], 'productName'));
        if($index!==false){
            $_SESSION['cart'][$index]['quantity'] += $temp['quantity'];

        }else {
            array_push($_SESSION['cart'], $temp);
        }
    }
?>
<!doctype html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medic</title>
</head>

<body>
<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Medicine Trading</a>
    <form class="form-inline" method="get" action="search.php">
        <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <form class="form-inline" action="order.php">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Cart
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <?php
                if($bool==TRUE){
                    foreach ($_SESSION['cart'] as $item){
                        echo "<a class=\"dropdown-item\" href=\"#\"> Name:".$item['productName']."</a>";
                        echo "<a class=\"dropdown-item\" href=\"#\"> Quantity:".$item['quantity']."</a>";
                    }
                }
                ?>
            </div>
        </div>
        <button class="btn btn-outline-success my-2 my-sm-0 ml-4" href="order.php">Order</button>
    </form>
</nav>

<div style="width: 90%; margin= 0; margin:  auto; margin-top:30px;">
    <h4 style="text-align: center">List of all products</h4>
<table class="table table-bordered table-dark mt-5">
    <thead>
    <tr>
        <th scope="col">Product Name</th>
        <th scope="col">Product Info</th>
        <th scope="col">Product Price</th>
        <th scope="col">Manufacture Date</th>
        <th scope="col">Expire Date</th>
        <th scope="col">Quantity</th>
    </tr>
    </thead>

    <tbody>
    <?php
        foreach ($row as $item){
            echo "<tr>";
            echo "<td>$item[1]</td><td>$item[2]</td><td>$item[3]</td><td>$item[4]</td><td>$item[5]</td>";
            echo "<td>
                    <form action='index.php' method='get'>
                    <input type='hidden' name='productName' value='$item[1]'>
                    <input type='hidden' name='price' value='$item[3]'>
                    <input type = 'number' min = '1' name = 'quantity' size='2'>
                    <button type='submit'>Add</button></form>
                  </td>";
            echo "</tr>";
        }
    ?>
    </tbody>
</table>
</div>




<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>