<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/29
 * Time: 20:15
 */
$con = mysqli_connect("192.168.0.107","root","123456")or die("connect error:".mysqli_error($con));
mysqli_select_db($con,"micri_db") or die("db error:".mysqli_error($con));
mysqli_query($con,"set names utf8");