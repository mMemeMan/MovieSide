<?php
include("session.php");
include("config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM movies WHERE id = $id"; // Modify query to fetch movie details
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
                   <!-- Movie details aligned to the right -->
                   <h1 class="mt-5"><?php echo $row['name']?></h1>
                   <ul class="list-group mt-3">
                       <li class="list-group-item"><strong>Description:</strong> <?php echo $row['description']; ?></li>
                       <li class="list-group-item"><strong>Year:</strong> <?php echo $row['year']; ?></li>
                       <li class="list-group-item"><strong>Category ID:</strong> <?php echo $row['category_id']; ?></li>
                       <li class="list-group-item"><strong>Country:</strong> <?php echo $row['country']; ?></li>
                       <?php
                       $average_query = "SELECT AVG(rating) AS avg_rating FROM comments WHERE movie_id = $id";
                       $average_result = mysqli_query($conn, $average_query);

                       if ($average_result && mysqli_num_rows($average_result) > 0) {
                           $average_row = mysqli_fetch_assoc($average_result);
                           $avg_rating = $average_row['avg_rating'];
                           ?>
                           <li class="list-group-item"><strong>Average Rating: </strong><?php echo number_format($avg_rating, 2); ?>/5</li>
                           <?php
                       } else {
                           ?>
                           <li class="list-group-item"><strong>No ratings yet.</strong></li>
                           <?php
                       }
                       ?>

                   </ul>

                    <?php
                    $user_id = $_SESSION["id"]; // Assuming $_SESSION["id"] holds the user's ID

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
               $comments_query = "SELECT * FROM comments WHERE movie_id = $id"; // Assuming $id holds the movie ID
               $comments_result = mysqli_query($conn, $comments_query);

               if ($comments_result && mysqli_num_rows($comments_result) > 0) {
                   while ($comment = mysqli_fetch_assoc($comments_result)) {
                       ?>
                       <li class="list-group-item">
                           <strong>Nickname:</strong> <?php echo $comment['nick']; ?> <br>
                           <strong>Rating:</strong> <?php echo $comment['rating']; ?>/5 <br>
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
           <a href="index.php" class="btn btn-primary mt-3">Back to Main page</a>
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
              });
          </script>

              </div>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    </body>
</html>