<?php
session_start();
require './functions.php';

$data = fetchMovieDetails($_GET['movie']);
$movieID = fetchID($_GET['movie']);
$reviewsData = fetchReviews($movieID);
$recommendationsData = fetchRecommendations($movieID);
$movieAvailabilityOnProviders = fetchAvailableOnProviders($movieID);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  header('Location: ./movie.php?movie=' . $_POST['movie_name']);
  exit();
}

if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']) {

  $connection = new PDO("pgsql:host=localhost;port=5432;dbname=insight", 'postgres', 'dlord213');
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $checkFavorite = $connection->query("SELECT * FROM user_favorites
  WHERE user_id = " . $_SESSION['user_id'] . " AND movie_id = $movieID AND title = '" . $data['Title'] . "'")->fetch(PDO::FETCH_ASSOC);

  if ($checkFavorite) {
    $isFavorite = true;
  } else {
    $isFavorite = false;
  }
}

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php if ($data != 404) : ?>
    <title>Insight / <?php echo $data['Title'] ?></title>
  <?php else : ?>
    <title>Insight / Movie not found</title>
  <?php endif; ?>
  <script>
    function toggleCollapse(id) {
      var content = document.getElementById(id);
      content.classList.toggle('hidden');
    }
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./globals.css">
  <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
</head>

<body class="bg-slate-100 max-w-7xl w-full mx-auto">
  <?php require './components/header_component.php' ?>
  <?php if ($data != 404) : ?>
    <main class="my-4 flex flex-row">

      <!-- LEFT SIDE CONTAINER -->
      <div class="flex flex-col basis-[30%]">
        <img src=<?php echo $data['Poster'] ?> class="w-full rounded-lg">
        <div class="flex flex-col my-4 gap-2">
          <div class="flex flex-row gap-4 flex-nowrap items-center">
            <?php if (isset($_SESSION['isLoggedIn'])) : ?>
              <ion-icon name="heart" size="large" class="cursor-pointer <?php echo ($isFavorite) ? 'active' : ''; ?>" data-movie-id="<?php echo htmlspecialchars($movieID, ENT_QUOTES, 'UTF-8'); ?>" data-movie-title="<?php echo htmlspecialchars($data['Title'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class='red-bg'></div>
              </ion-icon>
            <?php endif; ?>
            <h1 class="text-4xl text-slate-800 font-[700]"><?php echo $data['Title'] ?></h1>
          </div>
          <div class="flex flex-row flex-wrap gap-2">
            <h1 class="text-xl text-slate-700 font-[500]"><?php echo $data['Year'] ?> •</h1>
            <h1 class="text-xl text-slate-700 font-[500]"><?php echo $data['Genre'] ?></h1>
          </div>
          <p class="text-slate-500"><?php echo $data['Plot'] ?></p>
        </div>
        <h1 class="text-xl text-slate-800 font-[500]">Director</h1>
        <div class="flex flex-row flex-wrap gap-2 mb-2">
          <p class="text-slate-500"><?php echo $data['Director'] ?></p>
        </div>
        <h1 class="text-xl text-slate-800 font-[500]">Writer</h1>
        <div class="flex flex-row flex-wrap gap-2 mb-2">
          <p class="text-slate-500"><?php echo $data['Writer'] ?></p>
        </div>
        <h1 class="text-xl text-slate-800 font-[500]">Ratings</h1>
        <div class="flex flex-row gap-1 flex-wrap my-1">
          <?php
          foreach ($data['Ratings'] as $rating) {
            echo "<h1 class='p-2 text-slate-100 bg-slate-800 rounded-lg'>" . $rating['Value'] . "<b> • " . $rating['Source'] . "</b></h1>";
          }
          ?>
        </div>
      </div>
      <!-- LEFT SIDE CONTAINER -->

      <!-- RIGHT SIDE CONTAINER -->
      <div class="flex flex-col px-4 gap-2 basis-[70%]">

        <!-- STREAMING PLATFORMS CONTAINER -->
        <div class="my-2 flex flex-col gap-2">
          <h1 class="text-4xl text-slate-800 font-[700]">Available to watch on</h1>
          <div class="flex flex-row flex-wrap gap-2">
            <?php foreach ($movieAvailabilityOnProviders as $source) : ?>
              <?php if ($source['type'] === 'sub') : ?>
                <a href="<?php echo htmlspecialchars($source['web_url'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="text-center bg-slate-800 text-slate-100 p-4 rounded-lg">
                  <h1><?php echo htmlspecialchars($source['name'], ENT_QUOTES, 'UTF-8'); ?></h1>
                </a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <!-- STREAMING PLATFORMS CONTAINER -->

        <!-- RECOMMENDATIONS CONTAINER -->
        <div class="flex flex-col gap-2">
          <h1 class="text-4xl text-slate-800 font-[700]">Recommendations</h1>
          <div class="splide" id="recommendations-container">
            <div class="splide__track">
              <ul class="splide__list flex flex-row">
                <?php foreach ($recommendationsData['results'] as $recommendation) : ?>
                  <li class="splide__slide flex flex-col px-2">
                    <a href="movie.php?movie=<?php echo $recommendation['title']; ?>">
                      <img src="https://image.tmdb.org/t/p/original/<?php echo $recommendation['poster_path']; ?>" class="w-full rounded-lg">
                      <h1 class="font-[700] text-slate-700 mt-2 text-lg"><?php echo $recommendation['title']; ?></h1>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          </div>
        </div>
        <!-- RECOMMENDATIONS CONTAINER -->

        <!-- REVIEWS PLATFORMS CONTAINER -->
        <div class="flex flex-row justify-between items-center">
          <h1 class="text-4xl text-slate-800 font-[700]">Reviews</h1>
          <button class="cursor-pointer block px-4 py-2 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" id="reviews-button" onclick="handleCollapsibleContainer('reviews-container')">View</button>
        </div>
        <div class="hidden flex flex-col gap-2" id="reviews-container">
          <?php foreach ($reviewsData['results'] as $review) : ?>
            <div class='flex flex-col p-2 rounded-lg gap-2 border border-gray-300'>
              <h1 class='text-slate-700'><b><?php echo $review['author']; ?></b> • <?php echo $review['author_details']['rating']; ?> / 10</h1>
              <div>
                <a href="<?php echo $review['url']; ?>" target='_blank' rel='noopener noreferrer'>
                  <p class='text-slate-600'><?php echo $review['content']; ?></p>
                </a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- REVIEWS PLATFORMS CONTAINER -->

      </div>
      <!-- RIGHT SIDE CONTAINER -->

    </main>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        new Splide('#recommendations-container', {
          perPage: 4,
          width: '896px',
          pagination: false
        }).mount();
      });

      function handleCollapsibleContainer(id_container) {
        document.getElementById(id_container).classList.toggle("hidden");
      }
      document.addEventListener('DOMContentLoaded', () => {
        const heartIcon = document.querySelector('ion-icon[name="heart"]');
        heartIcon.addEventListener('click', () => {
          const movieID = heartIcon.getAttribute('data-movie-id');
          const title = heartIcon.getAttribute('data-movie-title');
          const xhr = new XMLHttpRequest();
          xhr.open('POST', 'toggle_favorite.php', true);
          xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
          xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
              const response = JSON.parse(xhr.responseText);
              if (response.success) {
                heartIcon.classList.toggle('active');
              } else {
                alert('Failed to toggle favorite');
              }
            }
          };
          xhr.send(`movie_id=${movieID}&title=${title}`);
        });
      });
    </script>
  <?php endif; ?>
</body>

</html>