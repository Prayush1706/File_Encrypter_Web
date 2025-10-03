<?php
session_start();
if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_FILES['file'])){
$file=$_FILES['file'];
if($file['error']===UPLOAD_ERR_OK){
$content=file_get_contents($file['tmp_name']);
$lines=explode("\n",$content);
$encryptedContent="";
$allKeys=[];
foreach($lines as $line){
$lineKeys=[];
$encryptedLine="";
for($i=0;$i<strlen($line);$i++){
$char=$line[$i];
$key=rand(0,255);
$lineKeys[]=$key;
$encryptedChar=chr(((ord($char)+$key)%256));
$encryptedLine.=$encryptedChar;
}
$allKeys[]=$lineKeys;
$encryptedContent.=$encryptedLine."\n";
}
require_once 'db_config.php';
$originalFilename=basename($file['name']);
$encryptedFilename='encrypted_'.time().'_'.$originalFilename;
$keysJson=json_encode($allKeys);
$stmt=$conn->prepare("INSERT INTO encrypted_files (original_filename,encrypted_filename,encryption_keys,created_at) VALUES (?,?,?,NOW())");
$stmt->bind_param("sss",$originalFilename,$encryptedFilename,$keysJson);
if($stmt->execute()){
file_put_contents('../uploads/'.$encryptedFilename,$encryptedContent);
$_SESSION['success']="File encrypted successfully!";
$_SESSION['encrypted_filename']=$encryptedFilename;
$_SESSION['file_id']=$conn->insert_id;
}else{
$_SESSION['error']="Database error: ".$stmt->error;
}
$stmt->close();
$conn->close();
header("Location: encrypt.php");
exit();
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Encrypt File</title>
<link rel="stylesheet" href="../css/encrypt.css">
</head>
<body>
<div class="container">
<a href="../index.html" class="back-link">← Back to Home</a>
<h1>🔒 Encrypt File</h1>
<?php
if(isset($_SESSION['success'])){
echo '<div class="message success">'.$_SESSION['success'];
if(isset($_SESSION['encrypted_filename'])){
echo '<br><strong>Encrypted file:</strong> '.$_SESSION['encrypted_filename'];
echo '<br><strong>File ID:</strong> '.$_SESSION['file_id'];
echo '<br><a href="download.php?file='.urlencode($_SESSION['encrypted_filename']).'" class="download-link">Download Encrypted File</a>';
}
echo '</div>';
unset($_SESSION['success']);
unset($_SESSION['encrypted_filename']);
unset($_SESSION['file_id']);
}
if(isset($_SESSION['error'])){
echo '<div class="message error">'.$_SESSION['error'].'</div>';
unset($_SESSION['error']);
}
?>
<form method="POST" enctype="multipart/form-data" id="uploadForm">
<div class="upload-area">
<p style="margin-bottom:15px;">📄 Select a text file to encrypt</p>
<label for="file" class="file-label">Choose File</label>
<input type="file" id="file" name="file" accept=".txt" required onchange="showFileName()">
<div id="fileInfo" class="file-info"></div>
</div>
<button type="submit" class="btn">Encrypt File</button>
</form>
</div>
<script src="../js/encrypt.js"></script>
</body>
</html>