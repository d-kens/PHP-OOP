<?php
// check if value was posted

if($_POST){
    // include database and object file
    include_once 'config/Database.php';
    include_once 'objects/Product.php';

    // get database connection
    $database = new Database();
    $db = $database->getConnection();

    // prepare product object
    $product = new Product($db);

    // set product id to be deleted
    $product->id = $_POST['object_id'];

    // delete the product
    if($product->delete()){
        ?>
            <div class="alert alert-success">
                Product deleted
            </div>
        <?php
    }
    //if unable to delete product
    else{
        ?>
            <div class="alert alert-danger">
                Unable to delete product
            </div>
        <?php
    }
}