<?php

// configure pagination variables
// page given in url parameterDefault page is one
$page = isset($_GET['page']) ? $_GET['page'] : 1;
// set the number of records per page
$records_per_page = 5;
// calculate for the query limit clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
// Retrieve records here
// Include databse and object file
include_once 'config/Database.php';
include_once 'objects/Category.php';
include_once 'objects/Product.php';

// Instatiate database and objects
$database = new Database();
$db = $database->getConnection();

$product = new Product($db);
$category = new Category($db);

// query product
$stmt = $product->readAll($from_record_num, $records_per_page);
$num = $stmt->rowCount();



// set page header
$page_title = "Read Products";
include_once 'layout_header.php';


// contents will be here
?>
    <div class="right-button-margin">
        <a href="create_product.php" class="btn btn-defaul pull-right">Create Product</a>
    </div>

    <?php

        if($num>0){
            ?>

                <table class="table table-hover table-responsive table-bordered">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                    <?php

                    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        //print_r($row); --> Test for extract function
                        //The extract() function does array to variable conversion. 
                        //That is it converts array keys into variable names and array values into variable value. 
                        //In other words, we can say that the extract() function imports variables from an array to the symbol table
                        extract($row);
                        ?>
                        <tr>
                            <td><?php echo $name ?></td>
                            <td><?php echo $price ?></td>
                            <td><?php echo $description ?></td>
                            <td>
                                <?php

                                    $category->id = $category_id;
                                    $category->readName();
                                    echo $category->name;

                                ?>
                            </td>
                            <td>
                                <a href="read_one.php?id=<?php echo $id ?>" class="btn btn-primary left-margin">
                                    <span class="glyphicon glyphicon-list"></span> Read
                                </a>
                                <a href="update_product.php?id=<?php echo $id ?>" class="btn btn-primary left-margin">
                                    <span class="glyphicon glyphicon-list"></span> Edit
                                </a>
                                <a delete-id="<?php echo $id ?>" class="btn btn-danger delete-object">
                                    <span class="glyphicon glyphicon-remove"></span> Delete
                                </a>
                            </td>
                        </tr>

                        <?php
                    }

                    ?>
                </table>
                <?php
                // the page where this paging is used
                $page_url = "index.php?";
                
                // count all products in the database to calculate total pages
                $total_rows = $product->countAll();
                
                // paging buttons here
                include_once 'paging.php';
                ?>
            <?php
        }
        else{
            ?>

                <div class="alert alert-info">No products found</div>

            <?php
        }

    ?>



<?php


// set page footer
include_once 'layout_footer.php';