<?php 

 require_once "../inc/conn.php";
 if(isset($_POST['submit'])){
    $email=trim(htmlspecialchars($_POST['email']));
    $password=trim(htmlspecialchars($_POST['password']));
     //check mail 
     $query="select * from users where `email`='$email'";
     $result=mysqli_query($conn,$query);
     if(mysqli_num_rows($result)>0){   
        $user=mysqli_fetch_assoc($result);
        $oldPassword=$user['password'];
        $name=$user['name'];
        $id=$user['id'];
        $verify=password_verify($password,$oldPassword);
        if($verify){
            $_SESSION['user_id']=$id;
            $_SESSION['success']=["welcome $name"];
            header("location:../index.php");
        }else{
            $_SESSION['errors']=["wrong password"];
            header("location:../login.php");
        }
     }else{
        $_SESSION['errors']=["email not exist"];
        header("location:../login.php");
     } 
    

 }else{
    header("location:../login.php");
 }