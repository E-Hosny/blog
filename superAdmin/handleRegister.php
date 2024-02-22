<?php 

 require_once "../inc/conn.php";
 if(isset($_POST['submit'])){
    $name=trim(htmlspecialchars($_POST['name']));
    $email=trim(htmlspecialchars($_POST['email']));
    $password=trim(htmlspecialchars($_POST['password']));
    $phone=trim(htmlspecialchars($_POST['phone']));
    
    $errors=[];
    //validate 
    if($name==""){
        $errors[]="name is required";
    }elseif(strlen($name)>30){
        $errors[]="name is too large";
    }elseif(is_numeric($name)){
        $errors[]="name must be string";
    }

    if($email==""){
        $errors[]="email is required";
    }elseif(strlen($email)>30){
        $errors[]="email is too large";
    }elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[]="email not correct";
    }

    if($password==""){
        $errors[]="password is required";
    }elseif(strlen($password)<5){
        $errors[]="passsword must be more than 5";
    }

    if(empty($errors)){
        $passwordHashed=password_hash($password,PASSWORD_DEFAULT);
        //insert data
        $query="insert into users (`name`,`email`,`password`,`phone`) values ('$name','$email','$passwordHashed','$phone')";
        $result=mysqli_query($conn,$query);
        if($result){
            header("location:../login.php");
        }else{
            $_SESSION['errors']=["error while insert"];
            header("location:register.php");
        }
    }else{
        $_SESSION['errors']=$errors;
        header("location:register.php");
    }

 }else{
    header("location:register.php");
 }