<?php
include("menu.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change movie data</title>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Change movie data</h1>
        <?php
        include "config.php";

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $query = "SELECT * FROM movies WHERE id = $id";
            $result = mysqli_query($conn, $query);

            if ($result) {
                $row = mysqli_fetch_assoc($result);
        ?>
        <form action="updateMovie.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="4"><?php echo $row['description']; ?></textarea>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year:</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo $row['year']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="country" class="form-label">Country:</label>
                <input type="text" class="form-control" id="country" name="country" value="<?php echo $row['country']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image:</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <div class="mb-3">
                <label for="categories" class="form-label">Categories:</label>
                <?php
                $categories_query = "SELECT * FROM categories";
                $categories_result = mysqli_query($conn, $categories_query);

                if ($categories_result && mysqli_num_rows($categories_result) > 0) {
                    while ($category = mysqli_fetch_assoc($categories_result)) {
                        $checked = '';
                        $movie_id = $row['id'];
                        $category_id = $category['id'];
                        $movie_categories_query = "SELECT * FROM movie_categories WHERE movies_id = $movie_id AND categories_id = $category_id";
                        $movie_categories_result = mysqli_query($conn, $movie_categories_query);

                        if ($movie_categories_result && mysqli_num_rows($movie_categories_result) > 0) {
                            $checked = 'checked';
                        }

                        echo "<div class='form-check'>";
                        echo "<input class='form-check-input' type='checkbox' name='categories[]' value='{$category['id']}' $checked>";
                        echo "<label class='form-check-label'>{$category['name']}</label>";
                        echo "</div>";
                    }
                }
                ?>
            </div>


            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
        <?php
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
