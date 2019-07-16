<?php
include "model/studentModel.php"; 
session_start();
$csdr=new StudentModel();
$sid=$_SESSION['splitId'];
$file=$csdr->selectBlob($sid);
//print_r($file);
$file_type=$file['mime'];
$ubit_name=$file['ubit_name'];
if($file_type=='application/pdf' or $file_type=='application/x-pdf'){
$ft='pdf';	
}
else{
$ft='csv';
$file_type='text/csv';
}
$file_name=$ubit_name.'.'.$ft;
header("Content-Type:".$file_type);
header('Content-Disposition: attachment;filename='.$file_name);
header('Accept-Ranges: bytes');
header('Expires: 0');
header('Cache-Control: public, must-revalidate, max-age=0');
$s= $file['file'];
echo $s;
?>