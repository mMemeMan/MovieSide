<?php
include("session.php");
include("menu.php");
require("config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MovieSide</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <style>
        .wrapper {
            width: 1200px;
            margin: 0 auto;
        }

        .poster {
            width: 320px;
            height: 410px;
            margin-bottom: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .poster:hover {
            transform: scale(1.05); /* Increase size on hover */
        }

        .movie-column {
            width: 25%;
            padding: 0 15px;
        }

        .movie-title {
            margin-top: 5px;
            font-size: 16px;
            font-weight: bold;
            color: black; /* Set text color to black */
            transition: color 0.3s ease-in-out;
        }

        .movie-column:hover .movie-title {
            color: black; /* Change text color on hover */
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <?php
                require_once "config.php";
                $data = "SELECT * FROM movies";
                if ($rows = mysqli_query($conn, $data)) {
                    if (mysqli_num_rows($rows) > 0) {
                        while ($row = mysqli_fetch_array($rows)) {
                            ?>
                            <div class="col-md-3 text-center movie-column">
                                <a href="details.php?id=<?php echo $row['id']; ?>">
                                    <img src="<?php echo $row['img']; ?>" alt="<?php echo $row['name']; ?>" class="img-responsive poster">
                                    <p class="movie-title"><?php echo $row['name']; ?></p>
                                </a>
                            </div>
                            <?php
                        }
                        mysqli_free_result($rows);
                    } else {
                        echo "<p class='lead'><em>No records found.</em></p>";
                    }
                } else {
                    echo "ERROR: Could not able to execute $sql. " . mysqli_error($conn);
                }
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
</body>

</html>
