<?php
// Database connection details. Of course this isn't empty in the actual script.
$host = "localhost";
$username = "";
$password = "";
$database = "";

// Create a new PDO instance
$pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

/// dropbox functions

$url = $_POST['url'];
$objUUID = $_POST['objUUID'];

  $sql = "UPDATE vendor SET `VendingMachineURL` = '".$url."', `objectUUID` = '".$objUUID."' WHERE `objectUUID` = '".$objUUID."'";  
  $pdo -> query($sql);




//// vendor functions

// Variables received from the LSL script
$itemName = $_POST['itemName'];
$avatarUUID = $_POST['avatarUUID'];

echo $itemName;
echo $avatarUUID;




// Retrieve the most recently added or changed URL
$query = "SELECT VendingMachineURL FROM vendor LIMIT 1";
$stmt = $pdo->query($query);

if ($stmt && $stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $url = $row['VendingMachineURL'];
   echo $url;
    // Forward the item name and avatar UUID to the URL
    $postData = http_build_query([
        'itemName' => $itemName,
        'avatarUUID' => $avatarUUID
         
    ], "", $arg_separator = "~");

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => $postData
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    if ($result !== false) {
        echo "Forwarded item name and avatar UUID to: " . $url;
    } else {
        echo "Failed to forward item name and avatar UUID to: " . $url;
    }
} else {
    echo "No URL found in the VendingMachineURL table.";
}

// Close the database connection
$pdo = null;


?>
