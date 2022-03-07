<?php
// reterive one product will be here
// get the ID of the product to be edited
$id = isset($_GET['id']) ? $_GET['id'] : die('ERRO: missing ID.');

// include database and object files
include_once 'config/Database.php';
include_once 'objects/Category.php';
include_once 'objects/Product.php';

// get the database connectiom
$database = new Database();
$db = $database->getConnection();

// prepare objects -> instantiate objects with databse connection
$category = new Category($db);
$product = new Product($db);

// set the ID of the product to be edited
$product->id = $id;

// read details of the product to be edited
$product->readOne();

// set the page header
$page_title = "Update Product";
include_once 'layout_header.php';

// contents will be here
?>
    <div class="right-button-margin">
        <a href="index.php" class="btn brn-default pull-right">Read Product</a>
    </div>

    <!-- post code will be here -->
    <?php
        // if the from was submitted
        if($_POST){
            // set product property values
            $product->name = $_POST['name'];
            $product->price = $_POST['price'];
            $product->description = $_POST['description'];
            $product->category_id = $_POST['category_id'];

            // update the product
            if($product->update()) {
                ?>
                    <div class="alert alert-success alert-dismissable">
                        Product was updated
                    </div>
                <?php
            } 
            // if unable to update the product
            else{
                ?>
                    <div class="alert alert-success alert-dismissable">
                        Unable to update product
                    </div>
                <?php
            }
        }

    ?>
  
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}");?>" method="post">
        <table class='table table-hover table-responsive table-bordered'>
    
            <tr>
                <td>Name</td>
                <td><input type='text' name='name' value='<?php echo $product->name; ?>' class='form-control' /></td>
            </tr>
    
            <tr>
                <td>Price</td>
                <td><input type='text' name='price' value='<?php echo $product->price; ?>' class='form-control' /></td>
            </tr>
    
            <tr>
                <td>Description</td>
                <td><textarea name='description' class='form-control'><?php echo $product->description; ?></textarea></td>
            </tr>
    
            <tr>
                <td>Category</td>
                <td>
                    <!-- categories select drop-down will be here -->
                    <?php

                        $stmt = $category->read();
                        // put them in a select drop down
                        ?>
                            <select name="category_id"  class="form-control">
                                <option>Please Select...</option>
                                <?php
                                    while($row_category = $stmt->fetch(PDO::FETCH_ASSOC)){
                                        $category_id = $row_category['id'];
                                        $category_name = $row_category['name'];

                                        // cureent category of the product must be selcted
                                        if($product->category_id==$category_id){
                                            echo "<option value='$category_id' selected>";
                                        }else{
                                            echo "<option value='$category_id'>";
                                        }
                                  
                                        echo "$category_name</option>";
                                    }
                                ?>

                            </select>
                        <?php

                    ?>
                </td>
            </tr>
    
            <tr>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary">Update</button>
                </td>
            </tr>
    
        </table>
    </form>

<?php


// set the page footer
include_once 'layout_footer.php';