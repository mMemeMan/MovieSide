<?php
include("session.php");
include("config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM movies WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }

    $row = mysqli_fetch_assoc($result);
} else {
    die("Error");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Details</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</head>
    <?php
    include("menu.php");
    ?>
    <body>
       <div class="container">
           <div class="row">
               <div class="col-md-6">
                   <!-- Image aligned to the left -->
                   <div class="img-container">
                       <img src="<?php echo $row['img']; ?>"  height="800px" width="530px">
                   </div>
               </div>
               <div class="col-md-6">
                   <h1 class="mt-5"><?php echo $row['name']?></h1>
                   <ul class="list-group mt-3">
                       <li class="list-group-item"><strong>Description:</strong> <?php echo $row['description']; ?></li>
                       <li class="list-group-item"><strong>Year:</strong> <?php echo $row['year']; ?></li>
                       <li class="list-group-item">
                               <strong>Category:</strong>
                               <?php
                               $movie_id = $row['id'];
                               $categories_query = "SELECT name FROM categories c JOIN movie_categories mc ON c.id = mc.categories_id WHERE mc.movies_id = $movie_id";
                               $categories_result = mysqli_query($conn, $categories_query);

                               if ($categories_result && mysqli_num_rows($categories_result) > 0) {
                                   while ($category = mysqli_fetch_assoc($categories_result)) {
                                       echo $category['name'] . ", ";
                                   }
                               } else {
                                   echo "No categories assigned.";
                               }
                               ?>
                           </li>
                       <li class="list-group-item"><strong>Country:</strong> <?php echo $row['country']; ?></li>
                       <?php
                       $average_query = "SELECT AVG(rating) AS avg_rating FROM comments WHERE movie_id = $id";
                       $average_result = mysqli_query($conn, $average_query);

                       if ($average_result && mysqli_num_rows($average_result) > 0) {
                           $average_row = mysqli_fetch_assoc($average_result);
                           $avg_rating = $average_row['avg_rating'];
                           ?>
                           <li class="list-group-item"><strong>Average Rating: </strong><?php echo number_format($avg_rating, 2); ?>/10</li>
                           <?php
                       } else {
                           ?>
                           <li class="list-group-item"><strong>No ratings yet.</strong></li>
                           <?php
                       }
                       ?>

                   </ul>

                    <?php
                    $user_id = $_SESSION["id"];

                    $sqlCheckFavorite = "SELECT id FROM users_likes WHERE movie_id = $id AND user_id = $user_id";
                    $resultCheckFavorite = mysqli_query($conn, $sqlCheckFavorite);

                    if ($resultCheckFavorite && mysqli_num_rows($resultCheckFavorite) > 0) {
                        $added = true;
                    } else {
                        $added = false;
                    }

                    $text = $added ? "uploads/heart_red.png" : "uploads/heart.png";
                    echo "<img class='fav' id='fav-icon' data-movie='$id' src='$text' style='width: 35px; margin-top: 10px;'>";
                    ?>


                   <div class="mt-4">
                       <h3>Add a Comment</h3>
                       <form method="POST" action="insertComment.php">
                           <input type="hidden" name="movie_id" value="<?php echo $id; ?>"> <!-- Replace $movie_id with the movie's ID -->
                           <div class="mb-3">
                               <label type="hidden" class="form-label">Nickname: <?php echo $_SESSION['login']; ?></label>
                               <input type="hidden" class="form-control" name="nick" value="<?php echo $_SESSION['login']; ?>" required>
                           </div>
                           <div class="mb-3">
                               <label for="rating" class="form-label">Your Rating (1-10):</label>
                               <input type="number" class="form-control" id="rating" name="rating" min="1" max="10" required>
                           </div>
                           <div class="mb-3">
                               <label for="comment" class="form-label">Your Comment:</label>
                               <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                           </div>
                           </br>
                           <button type="submit" class="btn btn-primary">Submit</button>
                       </form>
                   </div>
               </div>
           </div>
           <h2>Comments</h2>
           <ul class="list-group mt-3">
               <?php
               $comments_query = "SELECT * FROM comments WHERE movie_id = $id";
               $comments_result = mysqli_query($conn, $comments_query);

               if ($comments_result && mysqli_num_rows($comments_result) > 0) {
                   while ($comment = mysqli_fetch_assoc($comments_result)) {
                       ?>
                       <li class="list-group-item">
                           <strong>Nickname:</strong> <?php echo $comment['nick']; ?> <br>
                           <strong>Rating:</strong> <?php echo $comment['rating']; ?>/10 <br>
                           <strong>Comment:</strong> <?php echo $comment['info']; ?> <br>
                           <i class="far fa-calendar"></i> <em><?php echo $comment['date']; ?></em>
                       </li>
                       <?php
                   }
               } else {
                   ?>
                   <li class="list-group-item">No comments yet.</li>
                   <?php
               }
               ?>
           </ul>

            <br>
            <br>
           <h2>Related Movies</h2>
           <div class="row">
               <?php
               $categories_query = "SELECT categories_id FROM movie_categories WHERE movies_id = $id";
               $categories_result = mysqli_query($conn, $categories_query);
               $current_movie_categories = [];
               while ($category = mysqli_fetch_assoc($categories_result)) {
                   $current_movie_categories[] = $category['categories_id'];
               }

               $related_movies_query = "SELECT DISTINCT m.id, m.name, m.img FROM movies m
                                       INNER JOIN movie_categories mc ON m.id = mc.movies_id
                                       WHERE mc.categories_id IN (" . implode(',', $current_movie_categories) . ")
                                       AND m.id <> $id LIMIT 4";
               $related_movies_result = mysqli_query($conn, $related_movies_query);

               if ($related_movies_result && mysqli_num_rows($related_movies_result) > 0) {
                   while ($related_movie = mysqli_fetch_assoc($related_movies_result)) {
                       ?>
                       <div class="col-md-3">
                           <div class="img-container">
                               <a href="details.php?id=<?php echo $related_movie['id']; ?>">
                                   <img src="<?php echo $related_movie['img']; ?>" height="300px" width="200px">
                               </a>
                               <p><?php echo $related_movie['name']; ?></p>
                           </div>
                       </div>
                       <?php
                   }
               } else {
                   echo "No related movies found.";
               }
               ?>
           </div>

            <br>
            <br>
           <a href="index.php" class="btn btn-primary mt-3">Back to Main page</a>
           <br>
           <br>
           <?php
               if (isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
                   echo '<div class="mt-4">
                       <a href="updateMovieForm.php?id=' . $id . '" class="btn btn-primary">Update data</a>
                   </div>';
               }
               ?>
            <?php
            if (isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {

                echo '
                <div class="mt-4">
                    <button class="btn btn-danger" id="deleteMovieBtn" data-bs-toggle="modal">Delete movie</button>
                </div>
                ';
            }
            ?>

       </div>
          <script>
              $(document).ready(function () {
                  $(".fav").on("click", function () {
                      const img = $(this);
                      const movie_id = img.data("movie"); // Update the data attribute to movie

                      $.post(
                          "addLike.php",
                          { movie_id: movie_id }, // Adjust the parameter to match movie_id
                          function (data) {
                              console.log(data);

                              if (data.trim().includes("Success")) { // Adjust based on the response
                                  const currentSrc = img.attr("src");
                                  const newSrc = currentSrc.includes("heart.png") ? "uploads/heart_red.png" : "uploads/heart.png";
                                  img.attr("src", newSrc);
                              }
                          }
                      );
                  });

                $("#deleteMovieBtn").on("click", function () {
                            if (confirm("Are you sure you want to delete this movie?")) {
                                $.post(
                                    "delete.php",
                                    { movie_id: <?php echo $id; ?> },
                                    function (data) {

                                        if (data.trim().includes("Success")) {
                                            alert("Movie deleted successfully");
                                            window.location.href = "index.php";
                                        } else {
                                            alert("Error deleting movie");
                                        }
                                    }
                                );
                            }
                        });
              });
          </script>

              </div>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    </body>
</html>