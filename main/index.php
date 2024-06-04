<?php
session_start();

require './functions.php';

$connection = new PDO("pgsql:host=localhost;port=5432;dbname=insight", 'postgres', 'dlord213');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['form_type'])) {
    if ($_POST['form_type'] == 'search') {
      header('Location: ./movie.php?movie=' . $_POST['movie_name']);
      exit();
    } elseif ($_POST['form_type'] == 'logout') {
      session_unset();
      session_destroy();
      session_abort();
      header('Location: ./index.php');
      exit();
    }
  }
}

$currentlyShowingData = fetchMovieLists("now_playing");
$popularMoviesData = fetchMovieLists("popular");
$topRatedMoviesData = fetchMovieLists("top_rated");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insight</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./globals.css">
  <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
</head>

<body class="bg-slate-100 max-w-7xl w-full mx-auto">

  <!-- HEADER COMPONENT -->
  <?php require './components/header_component.php' ?>
  <!-- HEADER COMPONENT -->

  <main class="flex flex-col min-h-[100vh] gap-2">

    <!-- THEATRES CONTAINER -->
    <div class="my-2 flex flex-col gap-2">
      <h1 class="text-slate-700 font-[700] text-2xl">Currently showing on theatres</h1>
      <div class="splide" id="theatres-container">
        <div class="splide__track">
          <ul class="splide__list">
            <?php foreach ($currentlyShowingData['results'] as $movie) : ?>
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
    </div>
    <!-- THEATRES CONTAINER -->

    <!-- TOP RATED CONTAINER -->
    <div class="my-2 flex flex-col gap-2">
      <h1 class="text-slate-700 font-[700] text-2xl">Top rated movies</h1>
      <div class="splide" id="top-rated-container">
        <div class="splide__track">
          <ul class="splide__list">
            <?php foreach ($topRatedMoviesData['results'] as $movie) : ?>
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
    </div>
    <!-- TOP RATED CONTAINER -->

    <!-- POPULAR CONTAINER -->
    <div class="my-2 flex flex-col gap-2">
      <h1 class="text-slate-700 font-[700] text-2xl">Popular movies</h1>
      <div class="splide" id="popular-container">
        <div class="splide__track">
          <ul class="splide__list">
            <?php foreach ($popularMoviesData['results'] as $movie) : ?>
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
    </div>
    <!-- POPULAR CONTAINER -->

  </main>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      new Splide('#theatres-container', {
        perPage: 4,
        pagination: false,
        lazyLoad: true
      }).mount();

      new Splide('#popular-container', {
        perPage: 4,
        pagination: false,
        lazyLoad: true
      }).mount();

      new Splide('#top-rated-container', {
        perPage: 4,
        pagination: false,
        lazyLoad: true
      }).mount();
    });
  </script>

</body>

</html>