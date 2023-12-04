<?php
require_once "config.php";

$moviesPerPage = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $moviesPerPage;
$output = '';

if (isset($_GET['category']) && $_GET['category'] !== '') {
    $category_id = $_GET['category'];
    $data = "SELECT m.* FROM movies m
             INNER JOIN movie_categories mc ON m.id = mc.movies_id
             WHERE mc.categories_id = $category_id
             LIMIT $moviesPerPage OFFSET $offset";
} else if (isset($_GET['sort']) && $_GET['sort'] !== '') {
      $sort = $_GET['sort'];
      $data = "SELECT m.*, AVG(c.rating) AS avg_rating
               FROM movies m
               LEFT JOIN comments c ON m.id = c.movie_id
               GROUP BY m.id
               ORDER BY avg_rating $sort
               LIMIT $moviesPerPage OFFSET $offset";
  } else {
    $data = "SELECT * FROM movies LIMIT $moviesPerPage OFFSET $offset";
}

if ($result = mysqli_query($conn, $data)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $movieId = $row['id'];
            $avgRatingQuery = "SELECT AVG(rating) AS avg_rating FROM comments WHERE movie_id = $movieId";
            $avgRatingResult = mysqli_query($conn, $avgRatingQuery);
            $avgRatingRow = mysqli_fetch_assoc($avgRatingResult);
            $avgRating = $avgRatingRow['avg_rating'];

            $output .= '<div class="col-md-3 text-center movie-column movie-hidden">';
            $output .= '<a href="details.php?id=' . $row['id'] . '">';
            $output .= '<img src="' . $row['img'] . '" alt="' . $row['name'] . '" class="img-responsive poster">';
            $output .= '<p class="movie-title">' . $row['name'] . '|'. number_format($avgRating, 2) . ' <img src="uploads/A_star.png" alt="star" style="width: 13px; height: 13px; margin-bottom: 3px;"></p>';
            $output .= '</a>';
            $output .= '</div>';
        }
        mysqli_free_result($result);
    } else {

    }
} else {
    $output = "ERROR: Could not able to execute $data. " . mysqli_error($conn);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Animated Movies</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="moviesStyles.css">
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <?php echo $output; ?>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('.movie-column').addClass('movie-hidden');
        });
    </script>
</body>

</html>
