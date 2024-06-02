<?php

require './functions.php';

$data = fetchMovieDetails($_GET['movie']);
$movieID = fetchID($_GET['movie']);
$reviewsData = fetchReviews($movieID);

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

  <?php if ($data != 404) : ?>
    <main class="my-4 w-full flex flex-row">
      <div class="flex flex-col basis-[25%]">
        <img src=<?php echo $data['Poster'] ?> class="w-full rounded-lg">
        <div class="flex flex-col my-4 gap-2">
          <div class="flex flex-row gap-4 flex-wrap items-center">
            <ion-icon name="heart" size="large" class="cursor-pointer">
              <div class='red-bg'></div>
            </ion-icon>
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
      let icon = document.querySelector('ion-icon');
      icon.onclick = function() {
        icon.classList.toggle('active');
      }
    </script>
  <?php endif; ?>
</body>

</html>