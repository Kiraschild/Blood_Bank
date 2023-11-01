<?php include 'session.php'; ?>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
      #sidebar{position:relative;margin-top:-20px}
      #content{position:relative;margin-left:210px}
      @media screen and (max-width: 600px) 
      {
        #content 
        {
          position:relative;margin-left:auto;margin-right:auto;
        }
      }
    </style>
  </head>
  <?php
    $name=$number=$email=$age=$gender=$blood_group=$address="";
    $nameErr=$numberErr=$emailErr=$ageErr=$genderErr=$blood_groupErr=$addressErr=$fileErr="";
    $name_flag=0;
    $number_flag=0;
    $email_flag=0;
    $age_flag=0;
    $gender_flag=0;
    $blood_group_flag=0;
    $address_flag=0;
    $flag=0;
    if(isset($_POST["submit"]))
    {
      if(!empty($_POST["name"]))
      {
        $name=$_POST["name"];
      }
      if(!empty($_POST['number']))
      {
        $number=$_POST['number'];
      }
      if(!empty($_POST['email']))
      {
        $email=$_POST['email'];
      }
      if(!empty($_POST['age']))
      {
        $age=$_POST['age'];
      }
      if(!empty($_POST['gender']))
      {
        $gender=$_POST['gender'];
      }
      if(!empty($_POST['blood']))
      {
        $blood_group=$_POST['blood'];
      }
      if(!empty($_POST['address']))
      {
        $address=$_POST['address'];
      }
    }
    if(empty($_POST["name"]) || empty($_POST["number"]) || empty($_POST["age"]) || empty($_POST['gender']) || $_POST['gender']=="Select One" || empty($_POST['blood']) || $_POST['blood']=="Select One" || empty($_POST['address']))
    {
      $flag=1;
    }
    if($_SERVER["REQUEST_METHOD"]=="POST")
    {
      if (empty($_POST["name"])) 
      {
        $nameErr = "Name is required";
      } 
      else 
      {
        $name = test_input($_POST["name"]);
        $name_flag=1;
        if (!preg_match("/^[a-zA-Z ]*$/",$name)) 
        {
          $nameErr = "Only letters and white space allowed"; 
          $name_flag=0;
        }
      }
      if(empty($_POST["number"]))
      {
        $numberErr="Mobile Number is required.";
      }
      else
      {
        $number=test_input($_POST["number"]);
        $number_flag=1;
        if(!preg_match("/^[1-9]\d{9}$/",$number))
        {
          $numberErr="Mobile Number Entered Is Not In Proper Format";
          $number_flag=0;
        }
      }
      if (empty($_POST["email"])) 
      {
        $email="";
      } 
      else 
      {
        $email = test_input($_POST["email"]);
        $email_flag=1;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) 
        {
          $emailErr = "Invalid email format"; 
          $email_flag=0;
        }
      }
      if(empty($_POST["age"]))
      {
        $ageErr="Age is required.";
      }
      else
      {
        $age=test_input($_POST["age"]);
        $age_flag=1;
        if(!preg_match("/^[1-9][0-9]{1,2}$/",$age))
        {
          $ageErr="Please enter correct age";
          $age_flag=0;
        }
      }
      if (empty($_POST["gender"]) || $_POST["gender"]=="Select One") 
      {
        $genderErr = "Gender is required";
      } 
      else 
      {
        $gender = test_input($_POST["gender"]);
        $gender_flag=1;
      }
      if (empty($_POST["blood"]) || $_POST["blood"]=="Select One") 
      {
        $blood_groupErr = "Blood Group is required";
      } 
      else 
      {
        $blood_group = test_input($_POST["blood"]);
        $blood_group_flag=1;
      }
      if (empty($_POST["address"])) 
      {
        $addressErr = "Address is required.";
      } 
      else 
      {
        $address = test_input($_POST["address"]);
        $address_flag=1;
      }
      if(empty($_FILES["the_file"]))
    {
      $fileErr="Uploading the file is required.";
    }
    else
    {
      $currentDirectory = getcwd();
      $uploadDirectory = "\uploads\\";
      $errors = []; // Store errors here
      $fileExtensionsAllowed = ['pdf']; // These will be the only file extensions allowed 
      $fileName = $_FILES['the_file']['name'];
      $fileSize = $_FILES['the_file']['size'];
      $fileTmpName  = $_FILES['the_file']['tmp_name'];
      $fileType = $_FILES['the_file']['type'];
      $fileerr=$_FILES['the_file']['error'];
      //echo $fileName;
      if($fileerr==0){

      //$fileExtension = strtolower(end(explode('.',$fileName)));

      $uploadPath = $currentDirectory . $uploadDirectory . basename($fileName); 

      if (isset($_POST['submit'])) {

        //if (! in_array($fileExtension,$fileExtensionsAllowed)) {
         // $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
        //}

        if ($fileSize > 4000000) {
          $errors[] = "File exceeds maximum size (4MB)";
        }

        if (empty($errors)) {
          $didUpload = move_uploaded_file($fileTmpName, $uploadPath);

          if ($didUpload) {
            //echo $uploadPath;
            //echo "The file " . basename($fileName) . " has been uploaded";
          } else {
            echo "An error occurred. Please contact the administrator.";
          }
        } else {
          foreach ($errors as $error) {
            echo $error . "These are the errors" . "\n";
          }
        }
      }
      }
    }
      
    }
    function test_input($data) 
    {
      $data = trim($data);
      $data = stripslashes($data);
      $data = htmlspecialchars($data);
      return $data;
    }
    if($name_flag==1 && $number_flag==1 && $email_flag==1 && $age_flag==1 && $gender_flag==1 && $blood_group_flag==1 && $address_flag==1)
    {
        //echo "hello";
        $conn=mysqli_connect("localhost","root","","blood_donation") or die("Connection error");
        $file = $_FILES["the_file"]["name"];
        $sql= "INSERT INTO donor_details(donor_name,donor_number,donor_mail,donor_age,donor_gender,donor_blood,donor_address,file_name) values('{$name}','{$number}','{$email}','{$age}','{$gender}','{$blood_group}','{$address}','{$file}')";
        $result=mysqli_query($conn,$sql) or die("query unsuccessful.");
        
        header("Location: http://localhost/BDMS/admin/donor_list.php");
        mysqli_close($conn);
    }

  ?>

  <body style="color:black">
    <?php
      include 'conn.php';
      if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    ?>
    <div id="header">
      <?php $active="add"; include 'header.php';
      ?>
    </div>
    <div id="sidebar">
      <?php include 'sidebar.php'; ?>
    </div>
    <div id="content">
      <div class="content-wrapper">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12 lg-12 sm-12">
              <h1 class="page-title">Add Donor</h1>
            </div>
          </div>
          <hr>
          <form name="donor" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post"  enctype="multipart/form-data">
            <div class="row">
              <div class="col-lg-4 mb-4"><br>
              <div class="font-italic">Full Name<span style="color:red">*</span></div>
              <div><input type="text" name="name" class="form-control" value="<?php echo $name; ?>"></div>
              <span class="error" style="color:red"><?php echo $nameErr;?></span>
              </div>
              <div class="col-lg-4 mb-4"><br>
              <div class="font-italic">Mobile Number<span style="color:red">*</span></div>
              <div><input type="text" name="number" class="form-control" value="<?php echo $number; ?>"></div>
              <span class="error" style="color:red"><?php echo $numberErr;?></span>
              </div>
              <div class="col-lg-4 mb-4"><br>
              <div class="font-italic">Email Id</div>
              <div><input type="email" name="email" class="form-control"  value="<?php echo $email; ?>"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4 mb-4"><br>
              <div class="font-italic">Age<span style="color:red">*</span></div>
              <div><input type="text" name="age" class="form-control"  value="<?php echo $age; ?>"></div>
              <span class="error" style="color:red"><?php echo $ageErr;?></span>
              </div>
              <div class="col-lg-4 mb-4"><br>
              <div class="font-italic">Gender<span style="color:red">*</span></div>
              <div>
                <select name="gender" class="form-control" required>
                <option value="Select One" <?= $gender == "Select One"? "selected":"";?>>Select</option>
                <option value="Male" <?= $gender == "Male"? "selected":"";?>>Male</option>
                <option value="Female" <?= $gender == "Female"? "selected":"";?>>Female</option>
                <span class="error" style="color:red"><?php echo $genderErr;?></span>
                </select>
              </div>
              </div>
              <div class="col-lg-4 mb-4"><br>
              <div class="font-italic">Blood Group<span style="color:red">*</span></div>
              <div>
                <select name="blood" class="form-control" required>
                <option value=""selected disabled>Select</option>
                <?php
                  include 'conn.php';
                  $sql= "select * from blood";
                  $result=mysqli_query($conn,$sql) or die("query unsuccessful.");
                  while($row=mysqli_fetch_assoc($result)){
                ?>
                <option value=" <?php echo $row['blood_id'] ?>"> <?php echo $row['blood_group'] ?> </option>
                <?php } ?>
                </select>
              </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-lg-4 mb-4">
              <div class="font-italic">Address<span style="color:red">*</span></div>
              <div><textarea class="form-control" name="address" ><?php echo $address; ?></textarea></div></div>
              <span class="error" style="color:red"><?php echo $addressErr;?></span>
              <div class="col-lg-4 mb-4">
              <div class="font-italic">Upload Adhaar Card:<span style="color:red">*</span></div>
              <div class="mb-4"><input type="file" name="the_file" id="fileToUpload" accept="application/pdf" class="form-control" required></div>
              <span class="error" style="color:red"><?php echo $fileErr;?></span>
              </div>
              </div> <br>
            <div class="row">
              <div class="col-lg-4 mb-4">
              <div><input type="submit" name="submit" class="btn btn-primary" value="Submit" style="cursor:pointer"></div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <?php
      if($flag==0 && $name_flag==1 && $number_flag==1 && $email_flag==1 && $age_flag==1 && $gender_flag==1 && $blood_group_flag==1 && $address_flag==1)
      {
        $nameErr = $emailErr = $genderErr = $numberErr = $blood_gourpErr= $ageErr =$addressErr= "";
	      $name = $email = $gender = $address = $number = $blood_group = $age = "" ;
	      echo '<script type="text/JavaScript">prompt("Form Submitted Successfully!!!");</script>';
      //header("http://localhost/BDMS/add_donor.php"); 
      }
    ?>
    </div>
    <?php
      }
      else {
          echo '<div class="alert alert-danger"><b> Please Login First To Access Admin Portal.</b></div>';
          ?>
          <form method="post" name="" action="login.php" class="form-horizontal">
            <div class="form-group">
              <div class="col-sm-8 col-sm-offset-4" style="float:left">
                <button class="btn btn-primary" name="submit" type="submit">Go to Login Page</button>
              </div>
            </div>
          </form>
      <?php }
      ?>
      
  </body>
</html>
