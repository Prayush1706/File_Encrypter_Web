<?php
if(isset($_GET['file'])){
$filename=basename($_GET['file']);
$filepath='../uploads/'.$filename;
if(file_exists($filepath)){
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Content-Length: '.filesize($filepath));
header('Cache-Control: must-revalidate');
header('Pragma: public');
ob_clean();
flush();
readfile($filepath);
exit();
}else{
echo "File not found.";
}
}else{
echo "No file specified.";
}
?>