<?php

$id = "";
$amount = "";
$com = "";
$note = "";
$type = "";
$name = "";
$mobile = "";

$errorMessage = "";
$successMessage = "";

  if($_SERVER['REQUEST_METHOD'] == 'POST'){
      $id = $_POST["id"];
      $amount = $_POST["amount"];
      $com = $_POST["com"];
      $note = $_POST["note"];
      $type = $_POST["type"];
      $name = $_POST["name"];
      $mobile = $_POST["mobile"];
      
      
          do{
              if( empty($id) || empty($amount) || empty($com) ){
                  $errorMessage = "All feeld are required";
                  break;
              }
              
              
              
              $servername = "localhost";
$username = "root";
$password = "Urmine@143";
$dbname = "rechargedb";


$conn = new mysqli($servername, $username, $password, $dbname);
$conn2 = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
if ($conn2->connect_error) {
  die("Connection failed: " . $conn2->connect_error);
}



$sql = "INSERT INTO recharge_data ( id, amount,company,note,type)
VALUES ('$id', '$amount','$com','$note','$type')";
              
if ($conn->query($sql) === TRUE) {
$bill = $conn->insert_id;

} else {
  echo "Error: " . $sql . "<br>" . $conn->error;

}
$conn->close();
              

$sql2 = "UPDATE `recharge_today` SET 
`$com` = `$com` + $amount, 
total = total + $amount,
daytotalcash = daytotalcash + $amount,
totalbal = totalbal + $amount,
cashtotal = cashtotal + $amount


  ORDER BY date DESC LIMIT 1";

if($type != "Cash"){
    
    $sql3 = "INSERT INTO recharge_credit (name,mobile,company,amount,id,type,bill_no)
VALUES ('$name','$mobile','$com','$amount','$id','$type','$bill')";
    
    if ($conn2->query($sql3) === TRUE) {
    
} else {
  echo "Error: " . $sql3 . "<br>" . $conn2->error;

}
    
     $sql4 = "UPDATE `recharge_today` SET 
     todaycredit = todaycredit + $amount, 
     credittotal = credittotal + $amount,
     cashtotal = cashtotal - $amount,
     daytotalcash = daytotalcash - $amount
     
         ORDER BY date DESC LIMIT 1";
    
    if ($conn2->query($sql4) === TRUE) {
    
} else {
  echo "Error: " . $sql4 . "<br>" . $conn2->error;

}
    
}



if ($conn2->query($sql2) === TRUE) {
    
} else {
  echo "Error: " . $sql2 . "<br>" . $conn2->error;

}

$conn2->close();


              
              
              
              
              
              
              
              $id = "";
              $amount = "";
              $com = "";
              $note = "";
              $type = "";
              $name = "";
              $mobile = "";
              
              
              $successMessage = "Added Success";
               
              
          }while(false);
  }


            

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="icon.gif" type="image/gif" sizes="32x32" content="width=device-width, initial-scale=1.0">
    <title>Thavayil Recharge</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    
        <script>

    function changetype(){
         var type = document.getElementById("type").value;
        
        if(type != "Cash"){

          document.getElementById("copen").style.display='block';
        }
        else{
          document.getElementById("copen").style.display='none';
        }

    }
    
    
            
    
    
    </script>
    
    

</head>
<body style="margin: 50px;">
    <h1>Add Recharge</h1>
    <br>
    <div class="container">
        
        <?php
        if(!empty($errorMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
  <strong>$errorMessage</strong>
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div> ";
        };
        
        ?>
        
        
        <form method="post">
      <div class="form-floating mb-3">
        <input type="number" class="form-control" id"id" name="id" value="<?php echo $id;?>" placeholder="Enter Number">
        <label for="id">ID/Number</label>
     </div>
        
        <div class="form-floating">
         <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $amount;?>" placeholder="Enter Amount">
        <label for="amount">Amount</label>
      </div>
        <br>
        
        <select name="com" value="<?php echo $com;?>" id="com" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
            <option value="" selected>Select Company</option>
            <option >SunDirect</option>
            <option >DishTV</option>
            <option >D2H</option>
            <option >Airtel</option>
            <option >TataPlay</option>
        </select>
        
        <div class="form-floating">
         <input type="text" class="form-control" id="note" name="note" value="<?php echo $note;?>" placeholder="Enter Note">
        <label for="note">Note</label>
      </div>
        <br>
        
        
        <select onchange="changetype()" name="type" id="type" value="<?php echo $type;?>" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
            <option >Cash</option>
            <option >Credit</option>
            <option >GPay</option>
        </select>
        
        <br>
        <div id="copen" style="display:none">
            
            <div class="form-floating">
         <input type="text" class="form-control" id="name" name="name" value="<?php echo $name;?>" placeholder="Enter Name">
        <label for="name">Name</label>
      </div>
        <br>
            
            <div class="form-floating">
         <input type="number" class="form-control" id ="number" name="mobile" value="<?php echo $mobile;?>" placeholder="Enter mobile">
        <label for="mobile">Mobile</label>
      </div>
        <br>
        
        
        </div>
            
            
           <?php
        if(!empty($successMessage)){
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
  <strong>$successMessage</strong>
  <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
</div> ";
        };
        
        ?>
            
            
        
        <div class="d-grid gap-2">
            <button class="btn btn-primary" type="submit">Add</button>
        </div>
        <br>
        <div class="d-grid gap-2">
            <a class="btn btn-dark" href='index.html' type="button">Back</a>
        </div>
    
    
    </form>
    </div>
    

   
</body>
</html>