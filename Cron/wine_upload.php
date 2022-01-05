<?php 
    
    require_once('class.phpmailer.php');

    $array = array();
    $csvData = array();
    $insertCount  = 0;
    $notAddCount = 0;
    $updateCount = 0;
    $rowCount = 1;
    $duplicateUpc = array();
    $upcArray = array();
    $duplicate = FALSE;
    $isInvalidKey = FALSE;
    $wine_Id = '';
    $isBlankData = FALSE;
    $notInsertUPC = array();
    $successMsg = '';
    $notInsertRowNumber = 1;
    $notInsertCount = 0;

      // CSV DATA Insert & Update..
      $rootPath = $_SERVER["DOCUMENT_ROOT"];
      $filePath = "/Applications/XAMPP/xamppfiles/htdocs/wine/oU6iW5oZ0fI8hV9z/RoyalWineData.csv";
      
    // Connect Data Dase...
    $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');

    // getImage_For_InsertUpdate();
    // echo "Hello";die;

    $csvFile = fopen($filePath, 'r');

    if ($csvFile !== FALSE) {

      while (($data = fgetcsv($csvFile, 0, ',')) !== FALSE) {
          if ($rowCount === 1) {
              $num = count($data);
              for ($i = 0; $i < $num; $i++) {
                  array_push($array, $data[$i]);
              }
          }
          else {
              $c = 0;
              foreach ($array as $key) {
                  $csvData[$rowCount - 1][$key] = $data[$c];
                  $c++;
              }
          }
          $rowCount++;
      }

    if(count($csvData) > 0) {

      foreach($csvData as $row){

        if (array_key_exists("UPC", $row) && array_key_exists("Name", $row) && array_key_exists("Brand", $row) && array_key_exists("BottleSize", $row) && array_key_exists("BottleUOM", $row)) {

            array_push($upcArray,$row['UPC']);

            //Check duplicate upsc codes
            if(isset($row['UPC']))
            {

                // Check data base UPC code exist Or Not.
                $wine_Id = check_UPC_FromDB($row['UPC']);

                if(!$wine_Id){
                    if(!in_array($row['UPC'], $duplicateUpc)){
                        array_push($duplicateUpc,$row['UPC']);
                    }
                    $duplicate = TRUE;
                }
            }

        }else{
            $isInvalidKey = TRUE;

            $inavlidColumn = array();
            if (!array_key_exists("UPC", $row)){
                array_push($inavlidColumn, 'UPC');
            }
            if (!array_key_exists("Name", $row)){
                array_push($inavlidColumn, 'Name');
            }
            if (!array_key_exists("Brand", $row)){
                array_push($inavlidColumn, 'Brand');
            }
            if (!array_key_exists("BottleSize", $row)){
                array_push($inavlidColumn, 'BottleSize');
            }
            if (!array_key_exists("BottleUOM", $row)){
                array_push($inavlidColumn, 'BottleUOM');
            }

            $inValidColumnName = implode(', ', $inavlidColumn);
        }
    }


    // Check Invalid key ..
    if(!$isInvalidKey) {

      foreach($csvData as $row){ 

        $notInsertRowNumber++;
          $flavour='royal';
          $company_type = '';

          if($row['UPC'] != '' && $row['Name'] != '' && $row['Brand'] != '' && $row['BottleSize'] != '' && $row['BottleUOM'] != ''){
              $isBlankData = FALSE;
          }else{
              $isBlankData = TRUE;
          }

          //Check Category Name
          $categoryId = 0;
          if ($row['WineType'] != ''){

              //Check Data base category exist Or Not.
              $categoryId = check_Category_FromDB($row['WineType']);
              
              if ($categoryId == 0){
          
                  $data_to_store = array(
                      'parent_id' => 0,
                      'name' => $row['WineType'],
                      'status' => 'active',
                      'created_by' => 7,
                      'created_on' => date('Y-m-d H:i:s')
                  );

                  // New category Insert..
                  $created_on = date('Y-m-d H:i:s');

                  $categoryId = createCategory(0,$row['WineType'],'active','7',$created_on);

              }
          }

          // Check data base UPC code exist Or Not.
          if(isset($row['UPC'])){
            $wine_Id = check_UPC_FromDB($row['UPC']);
          }
          if ($wine_Id == 1){
              $wine_Id = '';
          }

          
          $upc = $row['UPC'];
          $name = mysql_real_escape_string($row['Name']);
          $brand = mysql_real_escape_string($row['Brand']);
          $year = $row['Vintage'];
          $type = mysql_real_escape_string($row['Style']);
          $description = mysql_real_escape_string($row['TastingNote']);
          $UOM = mysql_real_escape_string($row['BottleUOM']);
          $size = mysql_real_escape_string($row['BottleSize']); 

          // echo $description;die;
/*
          $upc = $row['UPC'];
          $name = str_replace("'", "\'", $row['Name']);
          $brand =  str_replace("'", "\'", $row['Brand']);
          $year =  $row['Vintage'];
          $type =  str_replace("'", "\'", $row['Style']);
          $description =  str_replace("'", "\'", $row['TastingNote']);
          $UOM =  str_replace("'", "\'", $row['BottleUOM']);
          $size =  str_replace("'", "\'", $row['BottleSize']);
        */
         
          if($row['UPC']!='' && !$isBlankData && $row['UPC']!=0)
          {

            if($wine_Id != ''){
                  
              // Update csv data.
              $sql =  "UPDATE `wine` SET `upc_code`=$upc,`name`='$name',`brand`='$brand',`year`='$year',`type`='$type',`description`='$description',`size`='$size',`category_id`='$categoryId',`flavour`='$flavour',`company_type`='$company_type',`UOM`='$UOM' WHERE id=$wine_Id ";
      
              if ($dbConnect->query($sql) === TRUE) {
                    $updateCount++;
                    // echo "Success Update ";
                  }
              }else{

                  $sql = "INSERT INTO `wine`(`id`, `upc_code`, `name`, `brand`, `year`, `type`, `description`, `size`, `category_id`, `flavour`, `company_type`, `UOM`) VALUES ('',$upc,'$name','$brand','$year','$type','$description','$size','$categoryId','$flavour','$company_type','$UOM')";
                  if ($dbConnect->query($sql) === TRUE) {
                        $insertCount++;
                        // echo "Success Insert ";
                    }
              }
              }else{

                  // Not Inserted UPC..
                  $columnName = array();

                  if ($row['UPC'] == '' || $row['UPC'] == 0){
                      array_push($columnName, 'UPC');
                  }
                  if ($row['Name'] == ''){
                      array_push($columnName, 'Name');
                  }
                  if ($row['Brand'] == ''){
                      array_push($columnName, 'Brand');
                  }
                  if ($row['BottleSize'] == ''){
                      array_push($columnName, 'BottleSize');
                  }
                  if ($row['BottleUOM'] == ''){
                      array_push($columnName, 'BottleUOM');
                  }
                  $inValidKeyName = '( Row '.$notInsertRowNumber.':  Empty field - '.implode(', ', $columnName).')';
                  
                  array_push($notInsertUPC, $inValidKeyName);
              }
              
          // Status message with imported data count
          $notAddCount = (count($csvData) - ($insertCount + $updateCount));
          $uploadReport = 'Total Rows ('.count($csvData).') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.') | Not Inserted Details [ '.implode(', ', $notInsertUPC).' ] ';
          }
  
          $description = 'The product has been inserted/updated successfully.';
          $date_now = date("Y-m-d");

          // Mail for upload details.
          sendMail($date_now, $description, $uploadReport);
         
          // For Image Saving..
          getImage_For_InsertUpdate(); 
          
  }else{

      // Invalid Key Alert....
      $description = 'The product does not insert/update because the column name is invalid.';
      $date_now = date("Y-m-d");

      $uploadReport = 'Valid column : '.'('.$inValidColumnName.')'.' please checked the CSV ';

      // Mail for upload details.
      sendMail($date_now, $description, $uploadReport);
     
  }

  }else{

    // Blank CSV File Upload..
     $description = 'You have uploaded a blank CSV file, Please check and upload a correct CSV file.';
     $date_now = date("Y-m-d");

     // Mail for upload details.
     sendMail($date_now, $description);

     // For Image Save and Update..
     getImage_For_InsertUpdate(); 

  }
}



    function getImage_For_InsertUpdate(){
         
        $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');
        // For image...
        $ImagesArray = [];
        $file_display = ['jpg', 'jpeg', 'png'];
        $allFilesArray = scandir('/Applications/XAMPP/xamppfiles/htdocs/wine/oU6iW5oZ0fI8hV9z/');
        $notInsertImageUPC = array();
        $insertCount  = 0;
        // $invalidImageType = 
       
        foreach ($allFilesArray as $file) {
            $file_type = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($file_type, $file_display) == true) {
                $ImagesArray[] = $file;
            }
        }

         
        foreach ($ImagesArray as $imageName){

            $imageUPC = preg_replace('/\\.[^.\\s]{3,4}$/', '', $imageName);
    
            $source_url = '/Applications/XAMPP/xamppfiles/htdocs/wine/oU6iW5oZ0fI8hV9z/'.$imageName;
            $sourceProperties = getimagesize($source_url);
           
            if(!empty($sourceProperties)){

                // Check data base UPC code exist Or Not.
                if(isset($imageUPC)){
                  $wine_Id = check_UPC_FromDB($imageUPC);
              }

              if (!empty($wine_Id) && ($wine_Id != 1)) {

               
                $thumb_Destination_url = '/Applications/XAMPP/xamppfiles/htdocs/wine/assets/wine/thumb/';
                $wine_Destination_url = '/Applications/XAMPP/xamppfiles/htdocs/wine/assets/wine/';
                $fileNewName = 'wine-'.$imageName;
                $wine_quality = '10';
                $thumb_quality = '50';
                $imageType = $sourceProperties[2];


                $width = $sourceProperties[0];
                $height = $sourceProperties[1];
                $newWidth = 181; 
                $newHeight = ($height / $width) * $newWidth;
               
                switch ($imageType) {

                  case 3:
                      
                      $newImage = imagecreatetruecolor($newWidth, $newHeight) ; 
                      $image = imagecreatefrompng($source_url);
                      imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) ; 
            
                      imagejpeg($newImage, $thumb_Destination_url.$fileNewName, $thumb_quality); 
                      imagejpeg($image, $wine_Destination_url.$fileNewName, $wine_quality);
                      // imagejpeg($image, $thumb_Destination_url.$fileNewName, $quality); 
                      break;
      
                  case 2:
                      $newImage = imagecreatetruecolor($newWidth, $newHeight) ; 
                      $image = imagecreatefromjpeg($source_url);
                      imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height) ;

                      imagejpeg($newImage, $thumb_Destination_url.$fileNewName, $thumb_quality);
                      imagejpeg($image, $wine_Destination_url.$fileNewName, $wine_quality); 
                      
                      break;
    
                  default:
                      
                      // Invalid Image type(format)...
                      $inValidImageType = '('.$imageName.' : This is not an image file.)';
                      array_push($notInsertImageUPC, $inValidImageType);

                      exit;
                      break;
              }
      
              // Image Insert or Update database.
            $isuploadSuccess = saveImage_FromDB($wine_Id, $fileNewName);
           
            if ($isuploadSuccess){
                $insertCount++;

                // Move another folder.
                // move_uploaded_file($rootPath, $folderPath.$fileNewName);
             
               // Delete old folder..
                if (file_exists($source_url)) {
                    // unlink($source_url);
                    echo 'File has been deleted';
                } 
 
            }  
        }else{

          // Image name not found in product list...
          $inValidKeyName = '('.$imageName.' : This image not found in product list.)';
          array_push($notInsertImageUPC, $inValidKeyName);

          if (file_exists($source_url)) {
            // unlink($source_url);
            echo 'File has been deleted';
        } 

        }

      }else{

        // Image size error...
        $inValidKeyName = '('.$imageName.' : This is an invalid image file.)';
        array_push($notInsertImageUPC, $inValidKeyName);
         if (file_exists($source_url)) {
                    // unlink($source_url);
                    echo 'File has been deleted';
            } 
        
      }

    }

        // Status message with imported data count
        $notInsertCount = (count($ImagesArray) - $insertCount);

        $uploadReport = 'Total Images ('.count($ImagesArray).') | Inserted ('.$insertCount.') | Not Inserted ('.$notInsertCount.') | Not Inserted image [ '.implode(', ', $notInsertImageUPC).' ] ';

        $description = "The product image(s) has been inserted/updated successfully.";
        $date_now = date("Y-m-d");
   
        // Mail for upload details.
        sendMail($date_now, $description, $uploadReport);

}

function resize($width,$height) {
  $new_image = imagecreatetruecolor($width, $height);
  imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
  $this->image = $new_image;
} 

function check_UPC_FromDB($imageUPC=NULL){
    $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');

    $sql =  "SELECT `id`, `upc_code` FROM `wine` WHERE `upc_code` = $imageUPC AND `status` = 'active' AND `is_deleted` = 0";
    
    $query = mysqli_query($dbConnect, $sql);
    
    $upcExistResult = mysqli_fetch_object($query);

    if(count($upcExistResult) > 0){
        return $upcExistResult->id;
    }else{
        return FALSE;
    }
 }

 function check_Category_FromDB($type=NULL){

    $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');

    $sql = "SELECT `name`, `id`, `parent_id` FROM `category` WHERE `name` = '$type' AND `status` = 'active' AND `is_deleted` = 0";
    $query = mysqli_query($dbConnect, $sql);
    $wineTypeResult = mysqli_fetch_object($query);

    if(count($wineTypeResult) > 0) {
        return $wineTypeResult->id;
    }else{
        return FALSE;
    }


 }

 function createCategory($parent_id, $name, $status, $created_by, $created_on){

    $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');

    $sql = "INSERT INTO `category`(`id`, `parent_id`, `name`, `status`, `created_by`, `created_on`) VALUES ('','$parent_id','$name','$status','$created_by','$created_on')";

    if ($dbConnect->query($sql) === TRUE) {
          return $dbConnect->insert_id;
        }else{
          return FALSE;
    }
 }

 function saveImage_FromDB($wineId, $image=NULL) {

        $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');

        $sql = "SELECT `id`, `wine_id` FROM `wine_images` WHERE `wine_images`.`wine_id` = '$wineId' AND `is_deleted` = 0 ORDER BY `wine_images`.`id` DESC";
       
        $query = mysqli_query($dbConnect, $sql);
        $result = mysqli_fetch_all($query, MYSQLI_ASSOC);

        if(count($result)>1){

          $sql = "UPDATE `wine_images` SET `is_deleted`='1' WHERE wine_id=$wineId";

          if ($dbConnect->query($sql) === TRUE) {
               //Insert Image ..
               imageInsert($wineId,$image);
            }else{
              return FALSE;
          }
        }

        $imageId = '';
        
        if(count($result)>0){
          $imageId = $result[0]['id'];
        }
       
        if($imageId != '') {
            // Update Image ...
            imageUpdate($wineId,$image,$imageId);
            return TRUE;
        }else{
            //Insert Image ..
           imageInsert($wineId,$image);
           return TRUE;

    }

 }

 function imageInsert($wineid=NULL,$image=NULL){

    $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');
    $sql = "INSERT INTO `wine_images`(`id`, `wine_id`, `image`) VALUES ('','$wineid','$image')";
    if ($dbConnect->query($sql) === TRUE) {
          return TRUE;
      }else{
          return FALSE;
    }

 }

 function imageUpdate($wineid=NULL,$image=NULL,$imageId=NULL){

    $dbConnect = mysqli_connect('localhost','root','','wyktsmhg_karossstagpro');

     $sql = "UPDATE `wine_images` SET `wine_id`='$wineid',`image`='$image' WHERE id=$imageId";
    if ($dbConnect->query($sql) === TRUE) {
        // echo "Update success";
        return TRUE;
      }else{
        return FALSE;
    }
 }


    // For Mail Send...
    function sendMail($uploadDate=NULL, $description=NULL, $uploadReport=NULL){
     
      // Admin mail - fraidy@thekgroupny.com
      // $email = 'ns.avalgate@gmail.com, vj.avalgate@gmail.com';

        $mail = new PHPMailer();
        $mail->CharSet =  "utf-8";
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Mailer = "smtp";
        $mail->Username = "noreply@karossonline.com";
        $mail->Password = 'lAmQMI8enGzUDqd';
        $mail->SMTPSecure = 'ssl';
    
        $mail->Host = "smtp.gmail.com";
        $mail->Port = "465";
    
        $mail->setFrom('noreply@karossonline.com', 'noreply@karossonline.com');
        $mail->AddAddress('ns.avalgate@gmail.com', 'tech');
    
        $mail->Subject  =  'WINE - Product uploading status';
        $mail->IsHTML(true);
      
    
        $header = '<!doctype html>
        <html>
        <head>
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
        <style>
        /* -------------------------------------
            GLOBAL
        ------------------------------------- */
        * {
          font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
          font-size: 100%;
          line-height: 1.6em;
          margin: 0;
          padding: 0;
        }
        img {
          width: auto;
        }
        body {
          -webkit-font-smoothing: antialiased;
          height: 100%;
          -webkit-text-size-adjust: none;
          width: 100% !important;
        }
        /* -------------------------------------
            ELEMENTS
        ------------------------------------- */
        a {
          color: #348eda;
        }
        .padding {
          padding: 10px 0;
        }
        /* -------------------------------------
            BODY
        ------------------------------------- */
        table.body-wrap {
          padding: 0;
          width: 100%;
          border: 5px solid #C48F29;
        }
        table.body-wrap .container {
          border: 1px solid #ccc;
        }
        /* -------------------------------------
            FOOTER
        ------------------------------------- */
        table.footer-wrap {
          clear: both !important;
          width: 100%;
          margin-top:20px;
        }
        .footer-wrap .container p {
          color: #666666;
          font-size: 12px;
        
        }
        table.footer-wrap a {
          color: #999999;
        }
        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3 {
          color: #111111;
          font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
          font-weight: 200;
          line-height: 1.2em;
          margin: 40px 0 10px;
        }
        h1 {
          font-size: 36px;
        }
        h2 {
          font-size: 28px;
        }
        h3 {
          font-size: 22px;
        }
        p,
        ul,
        ol {
          font-size: 14px;
          font-weight: normal;
          margin-bottom: 10px;
        }
        ul li,
        ol li {
          margin-left: 5px;
          list-style-position: inside;
        }
        /* ---------------------------------------------------
            RESPONSIVENESS
        ------------------------------------------------------ */
        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
          clear: both !important;
          display: block !important;
          margin: 0 auto !important;
        }
        /* Set the padding on the td rather than the div for Outlook compatibility */
        .body-wrap .container {
          padding: 20px;
        }
        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
          display: block;
          /*margin: 0 auto;*/
        }
        /* Lets make sure tables in the content area are 100% wide */
        .content table {
          width: 100%;
        }
        
        /**/
        .logo-wrap {
          margin: 10px 0 0;
          width:100%;
          text-align:left;
        }
        .logo-wrap, .content, table.body-wrap, img{
          max-width: 600px;
        }
        </style>
        </head>
        <body>
        
        <!-- body -->
        <table class="body-wrap" cellpadding="0" cellspacing="0">
          <tr>
            <td class="container">
              <!-- content -->
              <div class="content">
              <table>
                <tr>
                  <td>';
        
                $footer = '</td>
                </tr>
              </table>
              </div>
              <!-- /content -->
            </td>
            <td></td>
          </tr>
        </table>
        <!-- /body -->
        <!-- footer -->
        <table class="footer-wrap">
          <tr>
            <td></td>
            <td class="container">
              <!-- content -->
              <div class="content">
                <table>
                  <tr>
                    <td align="center">
                      <p>
                      </p>
                    </td>
                  </tr>
                </table>
              </div>
              <!-- /content -->
            </td>
            <td></td>
          </tr>
        </table>
        <!-- /footer -->
        </body>
        </html>';

        $message = "<p><strong>Dear Admin,</strong><br/></p>
          <p>".$description."</p><br/>
          <p><strong>UPLOAD INFO : </strong></p>
          <p>Upload Date - ".$uploadDate."</p>
          <p>".$uploadReport."</p>";

        $message .= "<p>Thank you,<br /><i><strong>WINE</strong></i></p>";
        

        $htmlContent = $header.$message.$footer;
        $mail->Body    = $htmlContent;
         
        if($mail->Send())
        {
            echo "Message was Successfully Send :)";
        }
        else
        {
            echo "Mail Error - >".$mail->ErrorInfo;
        }
    }


    /*
     $mail = new PHPMailer();
            $mail->CharSet =  "utf-8";
            $mail->IsSMTP();
            $mail->SMTPAuth = true;
            $mail->Mailer = "smtp";
            $mail->Username = "noreply@karossonline.com";
            $mail->Password = 'lAmQMI8enGzUDqd';
            $mail->SMTPSecure = 'ssl';

            $mail->Host = "smtp.gmail.com";
            $mail->Port = "465";

        
            $mail->setFrom('noreply@karossonline.com', 'noreply@karossonline.com');
            $mail->AddAddress('ns.avalgate@gmail.com ', 'tech');
        
            $mail->Subject  =  'Test Working on Server';
            $mail->IsHTML(true);
            $mail->Body    = 'Hi there ,
                                <br />
                                this Test mail was sent using SMTP...
                                <br />
                                cheers... :)';
        
            if($mail->Send())
            {
                echo "Message was Successfully Send :)";
            }

    */

?>