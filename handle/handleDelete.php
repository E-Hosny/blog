<?php
require_once "../inc/conn.php";
if(!isset($_SESSION['user_id'])){
    header("location:login.php");
 }
 
if(!isset($_GET['id'])){
    header("location:index.php");
 }
 $id=$_GET['id'];
 $query="select * from posts where id=$id";
 $result=mysqli_query($conn,$query);
 if(mysqli_num_rows($result)==1){
    $oldImg=mysqli_fetch_assoc($result)['image'];
    if(!empty($oldImg)){
        unlink("../assets/images/postImage/$oldImg");
    }
    $query="delete from posts where id=$id";
    $result=mysqli_query($conn,$query);
    if($result){
        $_SESSION['success']="post deleted succefully";
        header("location:../index.php");
    }else{
        $_SESSION['errors']=["error while delete"];
        header("location:../editPodt.php?id=$id");
    }
 }
 else{
  $_SESSION['errors']=["post not found"];
  header("location:../editPodt.php?id=$id");
 }