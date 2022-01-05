<?php 
ob_start();
include('index.php');
ob_end_clean();

$CI =& get_instance();
$CI->load->database();

$file_n = HTTP_ROYALWINEDATA_PATH;

$infoPath = pathinfo($file_n);
// if($infoPath['extension'] == 'csv'){
//     print_r("Welcome");die;
// }

$csv = array_map('str_getcsv', file(HTTP_ROYALWINEDATA_PATH));

array_shift($csv); //remove headers

$wine_Id = $this->Wine_model->checkDuplicateUpccodeReturnId('296211233');

print_r($wine_Id);die;

$row = 1;
if (($handle = fopen(HTTP_ROYALWINEDATA_PATH, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        // echo "<p> $num fields in line $row: <br /></p>\n";die;
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";die;
        }
    }
    fclose($handle);
}
   // Load CSV reader library
   $this->load->library('CSVReader');
                    
   // Parse data from CSV file
   $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
    
    print_r($csvData);die;
    // If file uploaded
    if(is_uploaded_file($_FILES['file']['tmp_name'])){
        // Load CSV reader library
        $this->load->library('CSVReader');

    }
// }


?>