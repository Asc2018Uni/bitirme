<?php
$url = 'https://app.nanonets.com/api/v2/OCR/Model/06b74b5e-e7ed-46af-9229-4396b5c7af26/LabelFile/';

$fileInput = $_FILES['fileInput']['tmp_name'];
$data = array('file' => new CURLFile($fileInput));

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERPWD, 'pzw12fGrf5o6nA0EwKJrr-LaWXCPAg3h:');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$json_string = $response;

// Parse the JSON string
$datas = json_decode($json_string, true);

// Extract the "label: ocr_text" values
$label_ocr_texts = array();
$ocr_texts = array();

foreach ($datas['result'][0]['prediction'] as $prediction) {
    $label = $prediction['label'];
    $ocr_text = $prediction['ocr_text'];
    $label_ocr_texts[] = "$label: $ocr_text";
    $ocr_texts[] = $ocr_text;
}

// Write the "label: ocr_text" values to a text file
$file = fopen('label_ocr_texts.txt', 'w');
foreach ($label_ocr_texts as $item) {
    fwrite($file, $item . PHP_EOL);
}
fclose($file);

// Establish a connection to the MySQL database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'project';

$cnx = mysqli_connect($host, $user, $password, $database);

if (!$cnx) {
    die("Connection failed: " . mysqli_connect_error());
}

// Mapping between labels and database column names
$column_mapping = array(
    'TARİH' => 'tarih',
    'SAAT' => 'saat',
    'ÖDEME' => 'odeme',
    'TOPLAM' => 'toplam'
);

// Prepare the insert query
$query = "INSERT INTO result (saat, tarih, toplam, odeme, img_link) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($cnx, $query);

// Prepare the data for insertion
$insert_data = array();
foreach ($label_ocr_texts as $item) {
    list($label, $value) = explode(': ', $item);
    $column = $column_mapping[$label];
    $insert_data[$column] = $value;
}

// Generate a unique filename for the uploaded image
$uniqueFilename = uniqid() . '.jpg';

// Set the destination path for the uploaded image
$destinationPath = 'Uploaded/' . $uniqueFilename;

// Move the uploaded file to the destination path
move_uploaded_file($_FILES['fileInput']['tmp_name'], $destinationPath);

// Bind the data to the prepared statement and execute
mysqli_stmt_bind_param($stmt, 'sssss', $insert_data['saat'], $insert_data['tarih'], $insert_data['toplam'], $insert_data['odeme'], $destinationPath);
mysqli_stmt_execute($stmt);
$num_rows = mysqli_stmt_affected_rows($stmt);

mysqli_stmt_close($stmt);
mysqli_close($cnx);

//echo $num_rows . " record inserted.";
header('Location: verify.php');
exit();
