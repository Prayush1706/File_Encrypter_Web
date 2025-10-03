function showFileName(){
    const fileInput=document.getElementById('file');
    const fileInfo=document.getElementById('fileInfo');
    if(fileInput.files.length>0){
        const fileName=fileInput.files[0].name;
        const fileSize=(fileInput.files[0].size/1024).toFixed(2);
        fileInfo.innerHTML=`<strong>Selected:</strong> ${fileName} (${fileSize} KB)`;
        fileInfo.style.display='block';
    }
}