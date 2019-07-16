<?php
include $_SERVER["DOCUMENT_ROOT"]."/ubspectrum/admin/crowdsource/model/adminModel.php";
session_start();
$csdr=new AdminModel();
$did=$_GET['dataset_id'];
// print_r($did);



if(isset($_GET['delete']) and ($_GET['delete']==true)):{
	$deleted=$csdr->deleteArchive($did);
	if($deleted==1){
		header("Location: /ubspectrum/admin/crowdsource/datasetsView.php");
	}
	else{
		echo "Please try again later";
	}
}

elseif($_GET['archive']==true):{
	 $archive=$csdr->archiveDataset($did);
	print_r($archive);
	return $archive;

}

elseif($_GET['undo']==true):{
	
	$undo=$csdr->undoArchive($did);
	return $undo;

}
elseif($_GET['publish']==true):{
	
	$publish=$csdr->publishDataset($did);
	return $publish;

}
elseif($_GET['unpublish']==true):{
	
	$unpublish=$csdr->unPublishDataset($did);
	return $unpublish;

}

elseif(isset($_GET['selectCurrent']) and ($_GET['selectCurrent']==true)):{
//echo "i";
	$file=$csdr->selectCurrentBlob($did);
	$file_type=$file['dataset_filetype'];
	$dataset_name=$file['dataset_name'];
	//print_r($file);
	if($file_type=='application/pdf' or $file_type=='application/x-pdf'){
	$ft='pdf';	
	}
	else{
	$ft='csv';
	$file_type='text/csv';
	}
	$file_name=$dataset_name.'.'.$ft;
	header("Content-Type:".$file_type);
	header('Content-Disposition: attachment;filename='.$file_name);
	header('Accept-Ranges: bytes');
	header('Expires: 0');
	header('Cache-Control: public, must-revalidate, max-age=0');
	$s= $file['dataset_file'];
	echo $s;
}
elseif(isset($_GET['download']) and ($_GET['download']==true)):{
	$file=$csdr->selectBlob($did);
	$file_type=$file['dataset_filetype'];
	$dataset_name=$file['dataset_name'];
	//print_r($file);
	if($file_type=='application/pdf' or $file_type=='application/x-pdf'){
	$ft='pdf';	
	}
	else{
	$ft='csv';
	$file_type='text/csv';
	}
	$file_name=$dataset_name.'.'.$ft;
	header("Content-Type:".$file_type);
	header('Content-Disposition: attachment;filename='.$file_name);
	header('Accept-Ranges: bytes');
	header('Expires: 0');
	header('Cache-Control: public, must-revalidate, max-age=0');
	$s= $file['dataset_file'];
	echo $s;
}
endif;
?>