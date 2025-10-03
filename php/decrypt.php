<?php
session_start();
require_once 'db_config.php';
if($_SERVER['REQUEST_METHOD']=='POST'&&isset($_POST['file_id'])){
$fileId=intval($_POST['file_id']);
$stmt=$conn->prepare("SELECT encrypted_filename,encryption_keys,original_filename FROM encrypted_files WHERE id=?");
$stmt->bind_param("i",$fileId);
$stmt->execute();
$result=$stmt->get_result();
if($row=$result->fetch_assoc()){
$encryptedFilename=$row['encrypted_filename'];
$keys=json_decode($row['encryption_keys'],true);
$originalFilename=$row['original_filename'];
$encryptedContent=file_get_contents('../uploads/'.$encryptedFilename);
$lines=explode("\n",$encryptedContent);
$decryptedContent="";
for($lineIdx=0;$lineIdx<count($lines);$lineIdx++){
$line=$lines[$lineIdx];
$lineKeys=isset($keys[$lineIdx])?$keys[$lineIdx]:[];
$decryptedLine="";
for($i=0;$i<strlen($line);$i++){
$char=$line[$i];
$key=isset($lineKeys[$i])?$lineKeys[$i]:0;
$charCode=ord($char)-$key;
if($charCode<0){
$charCode=($charCode%256)+256;
}else{
$charCode=$charCode%256;
}
$decryptedLine.=chr($charCode);
}
$decryptedContent.=$decryptedLine."\n";
}
$decryptedFilename='decrypted_'.time().'_'.$originalFilename;
file_put_contents('../uploads/'.$decryptedFilename,$decryptedContent);
$_SESSION['success']="File decrypted successfully!";
$_SESSION['decrypted_filename']=$decryptedFilename;
}else{
$_SESSION['error']="File not found in database.";
}
$stmt->close();
header("Location: decrypt.php");
exit();
}
$result=$conn->query("SELECT id,original_filename,encrypted_filename,created_at FROM encrypted_files ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Decrypt File</title>
<link rel="stylesheet" href="../css/decrypt.css">
</head>
<script src="../js/decrypt.js"></script>
<body>
<div class="container">
<a href="../index.html" class="back-link">← Back to Home</a>
<h1>🔓 Decrypt File</h1>
<?php
if(isset($_SESSION['success'])){
echo '<div class="message success">'.$_SESSION['success'];
if(isset($_SESSION['decrypted_filename'])){
echo '<br><strong>Decrypted file:</strong> '.$_SESSION['decrypted_filename'];
echo '<br><a href="download.php?file='.urlencode($_SESSION['decrypted_filename']).'" class="download-link">Download Decrypted File</a>';
}
echo '</div>';
unset($_SESSION['success']);
unset($_SESSION['decrypted_filename']);
}
if(isset($_SESSION['error'])){
echo '<div class="message error">'.$_SESSION['error'].'</div>';
unset($_SESSION['error']);
}
?>
<div class="file-list">
<?php
if($result&&$result->num_rows>0){
while($row=$result->fetch_assoc()){
echo '<div class="file-card">';
echo '<div class="file-info">';
echo '<h3>'.htmlspecialchars($row['original_filename']).'</h3>';
echo '<p>Encrypted as: '.htmlspecialchars($row['encrypted_filename']).'</p>';
echo '<p>Created: '.date('Y-m-d H:i:s',strtotime($row['created_at'])).'</p>';
echo '</div>';
echo '<form method="POST" style="margin:0;">';
echo '<input type="hidden" name="file_id" value="'.$row['id'].'">';
echo '<button type="submit" class="btn">Decrypt</button>';
echo '</form>';
echo '</div>';
}
}else{
echo '<div class="no-files">';
echo '<p>No encrypted files found.</p>';
echo '<a href="encrypt.php" class="action-link">Encrypt a file first →</a>';
echo '</div>';
}
$conn->close();
?>
</div>
</div>
</body>
</html>