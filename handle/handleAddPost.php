<?php 
 require_once "../inc/conn.php";
 if(!isset($_SESSION['user_id'])){
    header("location:login.php");
 }
    
    $user_id=$_SESSION['user_id'];

 if(isset($_POST['submit'])){
    $title=$_POST['title'];
    $body=$_POST['body'];
    //validation
    $errors=[];
    if(empty($title)){
        $errors[]="title is required";
    }elseif(is_numeric($title)){
        $errors[]="title must be string";
    }

    if(empty($body)){
        $errors[]="body is required";
    }elseif(is_numeric($body)){
        $errors[]="body must be string";
    }

    if(isset($_FILES['image'])&& $_FILES['image']['name']){
        $img=$_FILES['image'];
        $imgName=$img['name'];
        $imgTmp=$img['tmp_name'];
        $ext=strtolower(pathinfo($imgName,PATHINFO_EXTENSION));
        $imgError=$img['error'];
        $imgSize=$img['size']/(1024*1024);
        $newName=uniqid().".".$ext;

         //img validation
         if($imgError!=0){
            $errors[]="image is required";
         }elseif($imgSize>5){
            $errors[]="image has large size";
         }elseif(in_array($ext,["jpg","jpeg","gif","png"])){
            $errors[]="image is not correct";
         }
    
    }else{
        $newName=null;
    }

    if(empty($errors)){
        $query="insert into posts (`title`,`body`,`image`,`user_id`) values ('$title','$body','$newName','$user_id')";
        $result=mysqli_query($conn,$query);
        if($result){
            if(isset($_FILES['image'])&& $_FILES['image']['name']){
                move_uploaded_file($imgTmp,"../assets/images/postImage/$newName");
            }
            $_SESSION['success']="post added succesfully";
            header("location:../index.php");
        }else{
            $_SESSION['errors']=["error while insert"];
        }

    }else{
        $_SESSION['errors']=$errors;
        header("location:../addPost.php");
    }

    

 }else{
    header("location:addPost.php");
 }