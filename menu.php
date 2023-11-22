<?php
include("session.php");
require("config.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
  </head>
  <style>
            .wrapper {
                width: 1200px;
                margin: 0 auto;
            }
  </style>
    <body>
 <div class="wrapper">
         <div class="page-header clearfix">
              <h2 class="pull-left" style="margin-bottom: 10px;">Hello, <?php echo $_SESSION['login']; ?></h2>
              <a href="logout.php" class="btn btn-danger pull-right">Logout</a>
              <a href="searchMyComments.php" class="btn btn-info pull-right" style="margin-right: 10px;">My comments</a>
              <a href="searchMyLikes.php" class="btn btn-info pull-right" style="margin-right: 10px;">My Likes</a>
              <a href="addMovieForm.php" class="btn btn-info pull-right" style="margin-right: 10px;">Add movie</a>
              <form class="form-inline pull-right" action="searchByName.php" method="post">
                   <div class="form-group">
                       <input type="text" class="form-control" placeholder="Search movie" name="search">
                   </div>
                  <button type="submit" class="btn btn-default pull-right" style="margin-right: 10px;">Search</button>
              </form>
         </div>
        </div>
    </body>
</html>