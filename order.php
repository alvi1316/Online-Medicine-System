<?php
    session_start();
    include 'dbmanager.php';
    $db = new dbmanager();
    $con=$db->dbConnection();
    $bool = true;
    $totalPrice = 0;
    $totalQuantity = 0;
    $productName = "";
    if(isset($_POST['placeOrder'])){
        $target_dir = "uploads/";
        $uploadOk = 1;
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $fileName = basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check ==false){
            echo "File is not an image.";
            $uploadOk = 0;
        }
        else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }else if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        }else if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
        }else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        $db->insertOrder($con,$_SESSION['tName'],$_POST['name'],$_SESSION['tQuantity'],$_SESSION['tPrice'],$_POST['address'],$fileName);
        unset($_SESSION['cart']);
        unset($_SESSION['tName']);
        unset($_SESSION['tPrice']);
        unset($_SESSION['tQuantity']);
    }
    if(!isset($_SESSION['cart'])){
        $_SESSION['cart'] = array();
        $bool = false;
    }
    if(isset($_GET["productName"]) && $_GET["quantity"] != 0){
        $temp['productName'] = $_GET["productName"];
        $temp['quantity'] = $_GET["quantity"];
        $index = array_search($temp['productName'], array_column($_SESSION['cart'], 'productName'));
        if($index!==false){
            $_SESSION['cart'][$index]['quantity'] += $temp['quantity'];

        }else {
            array_push($_SESSION['cart'], $temp);
        }
    }
    if(isset($_GET['remove'])){
        $index = array_search($_GET['remove'], array_column($_SESSION['cart'], 'productName'));
        array_splice($_SESSION['cart'], $index, $index+1);
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
    <title>Document</title>
</head>
<body>

<nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Medicine Trading</a>
    <form class="form-inline" method="get" action="search.php">
        <input class="form-control mr-sm-2" name="search" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
    <form class="form-inline" action="order.php">
            <div class="btn-group dropleft">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                View Cart Items
            </button>
            <div class="dropdown-menu">
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
    </form>
</nav>

<hr>
<h5 style="text-align: center">Cart</h5>
<hr>
<div style="width: 90%; margin= 0; margin:  auto; margin-top:30px;">
<table class="table table-bordered table-dark">
    <thead>
    <tr>
        <th scope="col">Product Name</th>
        <th scope="col">Product Quantity</th>
        <th scope="col">Price</th>
        <th scope="col">Option</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if($bool==TRUE){
        $i=0;
        foreach ($_SESSION['cart'] as $item){
            echo "<tr>";
            foreach ($item as $item1){
                echo "<td>$item1</td>";
            }
            echo "<td><form action='order.php' method='get'><input type='hidden' value = '".$item['productName']."' name='remove'><button type='submit'>remove</button></form></td>";
            echo "</tr>";
            $i++;
        }
    }
    ?>
    <tr>
        <td>Total</td>
        <td></td>
        <?php
        $_SESSION['tQuantity']="";
        foreach ($_SESSION['cart'] as $item){
            $totalPrice = $totalPrice + ($item['quantity']*$item['price']);
            $totalQuantity = $totalQuantity + $item['quantity'];
            $_SESSION['tQuantity'] = $_SESSION['tQuantity'] . strval($item['quantity']).",";
            $productName = $productName . $item['productName'].",";
        }
        $_SESSION['tPrice'] = $totalPrice;
        $_SESSION['tName'] = $productName;
        echo "<td>$totalPrice</td>";
        ?>
        <td></td>
    </tr>
    </tbody>
</table>
</div>
<div style="width: 90%; margin= 0; margin:  auto; margin-top:30px;">
<div style="width: 600px">
<form action="order.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label>Enter Name</label>
        <input type="text" class="form-control" name="name" placeholder="Enter name">
    </div>
    <div class="form-group">
        <label>Address</label>
        <input type="Text" name="address" class="form-control" placeholder="Address">
    </div>
    Select the image of the prescription:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <button type="submit" class="btn btn-primary" name="placeOrder">Place Order</button>
</form>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>