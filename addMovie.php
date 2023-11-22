<?php
include "config.php";

register_shutdown_function(function(){
	if (error_get_last()) {
		var_export(error_get_last());
	}
});

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $category_id = $_POST["category_id"];
    $description = $_POST["description"];
    $year = $_POST["year"];
    $country = $_POST["country"];


    if (isset($_FILES['img'])) {
        $file = $_FILES['img'];
        $fileName = $_FILES['img']['name'];
        $fileTmpName = $_FILES['img']['tmp_name'];
        $fileDestination = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        $query = "INSERT INTO movies (name, category_id, description, img, year, country) VALUES ('$name', '$category_id', '$description', '$fileDestination', '$year', '$country')";


        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }

}
?>
