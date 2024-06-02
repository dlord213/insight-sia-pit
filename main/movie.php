<?php
session_start();
require './functions.php';

$data = fetchMovieDetails($_GET['movie']);
$movieID = fetchID($_GET['movie']);
$reviewsData = fetchReviews($movieID);

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
</head>

<body class="bg-slate-100 max-w-7xl w-full mx-auto">
  <header class="my-4 flex flex-row">
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-row gap-2">
      <a href="./index.php" class="cursor-pointer block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500">Home</a>
      <input type="text" name="movie_name" placeholder="Search by title" class="block p-4 text-slate-800 bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
      <input type="submit" value="Search" class="cursor-pointer block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
      </div>
    </form>
  </header>
  <?php if ($data != 404) : ?>
    <main class="my-4 w-full flex flex-row">
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
      </div>
      <div class="flex flex-col px-4 gap-2 basis-[70%]">
        <?php
        foreach ($reviewsData['results'] as $review) {
          echo "
            <div class='flex flex-col p-2 rounded-lg gap-2 border border-gray-300'>
              <h1 class='text-slate-700'><b>" . $review['author'] . "</b> • " . $review['author_details']['rating'] . " / 10</h1>
              <div>
                <a href=" . $review['url'] . " target='_blank' rel='noopener noreferrer'>
                  <p class='text-slate-600'>" . $review['content'] . "</p>
                </a>
              </div>
            </div>
          ";
        }
        ?>
      </div>
    </main>
    <script>
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