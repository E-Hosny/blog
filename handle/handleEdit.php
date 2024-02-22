<?php 

 require_once "../inc/conn.php";
 if(!isset($_SESSION['user_id'])){
    header("location:login.php");
 }

 if(isset($_POST['submit']) && $_GET['id']){
    $id=$_GET['id'];
    $query="select * from posts where id=$id";
    $result=mysqli_query($conn,$query);
    if(mysqli_num_rows($result)==1){
        $oldImg=mysqli_fetch_assoc($result)['image'];
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
             }elseif($imgSize>1){
                $errors[]="image has large size";
             }elseif(in_array($ext,["jpg","jpeg","gif","png"])){
                $errors[]="image is not correct";
             }
        
        }else{
            $newName=$oldImg;
        }

        if(empty($errors)){
            $query="update posts set `title`='$title',`body`='$body',`image`='$newName' where id=$id";
            $result=mysqli_query($conn,$query);
            if($result){
                if(isset($_FILES['image'])&& $_FILES['image']['name']){
                    unlink("../assets/images/postImage/$oldImg");
                    move_uploaded_file($imgTmp,"../assets/images/postImage/$newName");
                }
                $_SESSION['success']="post updated succesfully";
                header("location:../viewPost.php?id=$id");
            }else{
                $_SESSION['errors']=["error while update"];
                header("location:../editPost.php?id=$id");
            }
    
        }else{
            $_SESSION['errors']=$errors;
            header("location:../editPost.php?id=$id");
        }

    }else{
        $_SESSION['errors']=["post not found"];
        header("location:../index.php");
    }

 }else{
   header("location:../index.php");
 }