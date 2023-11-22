<?php
include("session.php");
include("menu.php");
require("config.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results - MovieSide</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style>
        .wrapper {
            width: 1200px;
            margin: 0 auto;
        }
        .poster {
            width: 200px; /* Width of each poster */
            height: 300px;
            margin-bottom: 40px; /* Spacing between posters */
        }
        .movie-column {
            width: 25%;
            padding: 0 15px; /* Adjust spacing between columns */
        }
        .movie-title {
            margin-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <?php
                if (isset($_POST['search'])) {
                    $search = mysqli_real_escape_string($conn, $_POST['search']);

                    $query = "SELECT * FROM movies WHERE name LIKE '%$search%'";
                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <div class="col-md-3 text-center movie-column">
                                <a href="details.php?id=<?php echo $row['id']; ?>"> <!-- Link to details.php with movie ID -->
                                    <img src="<?php echo $row['img']; ?>" alt="<?php echo $row['name']; ?>" class="img-responsive poster">
                                    <p class="movie-title"><?php echo $row['name']; ?></p>
                                </a>
                            </div>
                            <?php
                        }
                        mysqli_free_result($result);
                    } else {
                        echo "<p class='lead'><em>No records found.</em></p>";
                    }
                } else {
                    echo "<p class='lead'><em>No search term provided.</em></p>";
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
</body>
</html>
