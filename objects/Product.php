<?php

class Product {
    // Database connection and table name
    private $conn;
    private $table_name = "products";

    // object properties
    public $id;
    public $name;
    public $price;
    public $description;
    public $category_id;
    public $image;
    public $timestamp;
    

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // create produc
    function create() {
        // write query
        $query = "INSERT INTO " . $this->table_name . "
            SET name=:name, price=:price, description=:description,
                category_id=:category_id, image=:image, created=:created";

        $stmt = $this->conn->prepare($query);

        // sanitize posted values
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->category_id = htmlspecialchars(strip_tags($this->category_id));
        $this->image=htmlspecialchars(strip_tags($this->image));

        // to get the timestamp for 'created field'
        $this->timestamp = date('Y-m-d H:i:s');

        // bind values
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":image", $this->image);
        $stmt->bindParam(":created", $this->timestamp);
        

        if($stmt->execute()){
            return true;
        }
        else{
            return false;
        }
    }

    // Retrieving all product records
    function readAll($from_record_num, $records_per_page) {
        $query = "SELECT id, name, description, price, category_id FROM " .$this->table_name ." ORDER BY name ASC LIMIT {$from_record_num},{$records_per_page} ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
    
    // read a single product from the database
    function readOne() {
        $query = "SELECT name, price, description, category_id, image FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->name = $row['name'];
        $this->price = $row['price'];
        $this->description= $row['description'];
        $this->category_id = $row['category_id'];
        $this->image = $row['image'];
    }
   
    // used for paging products
    public function countAll(){
    
        $query = "SELECT id FROM " . $this->table_name . "";
    
        $stmt = $this->conn->prepare( $query );
        $stmt->execute();
    
        $num = $stmt->rowCount();
    
        return $num;
    }
    //update code
    function update(){
  
        $query = "UPDATE
                    " . $this->table_name . "
                SET
                    name = :name,
                    price = :price,
                    description = :description,
                    category_id  = :category_id
                WHERE
                    id = :id";
      
        $stmt = $this->conn->prepare($query);
      
        // posted values
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->price=htmlspecialchars(strip_tags($this->price));
        $this->description=htmlspecialchars(strip_tags($this->description));
        $this->category_id=htmlspecialchars(strip_tags($this->category_id));
        $this->id=htmlspecialchars(strip_tags($this->id));
      
        // bind parameters
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':price', $this->price);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':category_id', $this->category_id);
        $stmt->bindParam(':id', $this->id);
      
        // execute the query
        if($stmt->execute()){
            return true;
        }
      
        return false;
          
    }

    // delete the product
    function delete(){
    
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
    
        if($stmt->execute()){
            return true;
        }else{
            return false;
        }
    }

    // will upload image file to the server
    function uploadPhoto() {
        $result_message = "";

        // now, if the image is not empty, try to upload the image
        if($this->image){
            // sha1_file() function is used to make a unique file name
            $target_directory = "uploads/";
            $target_file = $target_directory . $this->image;
            $file_type = pathinfo($target_file, PATHINFO_EXTENSION);

            // error mesaage 
            $file_upload_error_message="";

            // make sure that file is a real image
            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if($check!==false){
                // submitted file is an image
            }else{
                $file_upload_error_message.="<div>Submitted file is not an image</div>";
            }

            // make sure certain file types are allowed
            $allowed_file_types = array("jpg", "jpeg", "png", "gif");
            if(!in_array($file_type, $allowed_file_types)){
                $file_upload_error_message.="<div>Only JPG, JPEG, PNG, GIF files are allowed</div>";
            }

            // make sure file does not exist
            if(file_exists($target_file)){
                $file_upload_error_message.="<div>Image already exists. Try change file name</div>";
            }

            // make sure submitted file is not too large, can't be larger than 1MB
            if($_FILES['image']['size'] > (1024000)){
                $file_upload_error_message.="<div>IMage must be less than 1 MB in size</div>";
            }

            // make sure the 'uploads' folder exists
            // if not, create it
            if(!is_dir(($target_directory))){
                mkdir($target_directory,0777,true);
            }

            // if $file_upload_error_message is still empty
            if(empty($file_upload_error_message)){
                // it means there are no errors, so try to upload
                if(move_uploaded_file($_FILES["image"]["tmp_name"],$target_file)){
                    // it means photo was uploades
                }else{
                    $result_message.="<div class='alert alert-danger'>";
                        $result_message.="<div>Unable to upload photo.</div>";
                        $result_message.="<div>Update the record to upload photo.</div>";
                    $result_message.="</div>";
                }
            }
            // if $file_upload_error_message is NOT empty
            else{
                // it means there are some errors, so show them to user
                $result_message.="<div class='alert alert-danger'>";
                    $result_message.="{$file_upload_error_message}";
                    $result_message.="<div>Update the record to upload photo.</div>";
                $result_message.="</div>";
            }


        }

        return $result_message;
    }
}