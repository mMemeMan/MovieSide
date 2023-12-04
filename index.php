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

       .footer {
           position: fixed;
           bottom: 10px;
           right: 10px;
           color: black;
           font-size: 14px;
       }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
        <div class="filter-container">
                            <label for="categoryFilter">Filter by Category:</label>
                            <select id="categoryFilter">
                                <option value="">All</option>
                                <?php
                                    $categories_query = "SELECT * FROM categories";
                                    $categories_result = mysqli_query($conn, $categories_query);

                                    if ($categories_result && mysqli_num_rows($categories_result) > 0) {
                                        while ($category = mysqli_fetch_assoc($categories_result)) {
                                            echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                        }
                                    }
                                ?>
                            </select>
                             <select id="sortRating">
                                                <option value="">All</option>
                                                 <option value="asc">Low rated first</option>
                                                 <option value="desc">Highly rated first</option>
                             </select>
                        </div>
            <div class="movies-container">

            </div>
        </div>
    </div>
    <br>
    <br>

    <script>
        $(document).ready(function () {
            var page = 1;
            var isLoading = false;
            var selectedCategory = '';
            var sortDirection = '';

            function loadMovies(category = '') {
                        $.ajax({
                            url: 'getMovies.php?page=' + page + '&category=' + category + '&sort=' + sortDirection,
                            type: 'get',
                            success: function (response) {
                                $('.movies-container').append(response);
                                page++;
                                isLoading = false;
                            }
                        });
                    }

            loadMovies();

            $('#categoryFilter').change(function () {
                var category = $(this).val();
                $('.movies-container').empty();
                page = 1;
                selectedCategory = category;

                if ($('.filter-container').find('#categoryFilter').length === 0) {
                    $('.filter-container').html(`
                        <label for="categoryFilter">Filter by Category:</label>
                        <select id="categoryFilter">
                            <option value="">All</option>
                            <?php
                                // Здесь нужно добавить PHP-код для получения списка категорий из базы данных
                                $categories_query = "SELECT * FROM categories";
                                $categories_result = mysqli_query($conn, $categories_query);

                                if ($categories_result && mysqli_num_rows($categories_result) > 0) {
                                    while ($category = mysqli_fetch_assoc($categories_result)) {
                                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                    }
                                }
                            ?>
                        </select>
                    `);
                }

                loadMovies(selectedCategory);
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !isLoading) {
                    isLoading = true;

                    if (selectedCategory !== '') {
                        loadMovies(selectedCategory);
                    } else {
                        loadMovies();
                    }
                }
            });

            $('#sortRating').change(function () {
                        sortDirection = $(this).val();
                        $('.movies-container').empty();
                        page = 1;
                        loadMovies(selectedCategory);
            });

        });
    </script>
</body>
</html>



