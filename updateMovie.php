<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $year = intval($_POST['year']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $categories = $_POST['categories'];

    $query = "UPDATE movies SET name = '$name', description = '$description', year = $year, country = '$country'";

    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $fileName = $_FILES['image']['name'];
        $fileTmpName = $_FILES['image']['tmp_name'];
        $fileDestination = 'uploads/' . $fileName;
        move_uploaded_file($fileTmpName, $fileDestination);

        $query .= ", img = '$fileDestination'";
    }

    $query .= " WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        $deleteCategoriesQuery = "DELETE FROM movie_categories WHERE movies_id = $id";
        mysqli_query($conn, $deleteCategoriesQuery);

        if (!empty($categories)) {
            foreach ($categories as $category_id) {
                $insertCategoryQuery = "INSERT INTO movie_categories (movies_id, categories_id) VALUES ($id, $category_id)";
                mysqli_query($conn, $insertCategoryQuery);
            }
        }

        header("Location: index.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
