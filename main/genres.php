<?php
session_start();
require './functions.php';

$moviesData = fetchMoviesByGenre($_GET['genre_id']);
$genreName = $_GET['genre_name'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insight / Discover</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./globals.css">
  <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
</head>

<body class="bg-slate-100 max-w-7xl w-full mx-auto">
  <?php require './components/header_component.php' ?>
  <main class="my-4 flex flex-col gap-2 relative">
    <h1 class="text-4xl text-slate-800 font-[700]"><?php echo $genreName; ?></h1>
    <div class="splide" id="movie-container">
      <div class="splide__track">
        <ul class="splide__list flex flex-row">
          <?php foreach ($moviesData['results'] as $movie) : ?>
            <li class="splide__slide flex flex-col px-2">
              <a href="movie.php?movie=<?php echo $movie['title']; ?>">
                <img src="https://image.tmdb.org/t/p/original/<?php echo $movie['poster_path']; ?>" class="w-full rounded-lg">
                <h1 class="font-[700] text-slate-700 mt-2 text-lg"><?php echo $movie['title']; ?></h1>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </main>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new Splide('#movie-container', {
        perPage: 4,
        width: '1280px',
        pagination: false
      }).mount();
    });
  </script>
</body>

</html>