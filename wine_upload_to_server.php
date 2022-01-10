<?php 
$content = "some text here";
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . "/wine/assets/wine_expense_details_image/log.txt","wb");
// print_r($fp);die;
fwrite($fp,$content);
fclose($fp);
echo "upload";die;
?>