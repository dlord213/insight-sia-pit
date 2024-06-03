<?php
session_start();


if (!isset($_SESSION['isLoggedIn'])) {
  header('Location: ./index.php');
  exit();
}

$connection = new PDO("pgsql:host=localhost;port=5432;dbname=insight", 'postgres', 'dlord213');
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$favorites = $connection->query("SELECT * FROM user_favorites
WHERE user_id = " . $_SESSION['user_id'])->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insight / Favorites</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./globals.css">
</head>

<body class="bg-slate-100 max-w-7xl w-full mx-auto">
  <main class="my-4 flex flex-col gap-2">

    <div>
      <h1 class="text-2xl font-[700] text-slate-800"><?php echo $_SESSION['username'] ?>'s favorites</h1>
      <p><?php echo $_SESSION['name'] ?></p>
      <p><?php echo $_SESSION['location'] ?></p>
    </div>
    <a href="./index.php" class='cursor-pointer p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500 max-w-[128px] text-center'>Home</a>
    <hr class="border border-slate-300">
    <?php if ($favorites) : ?>
      <div class="flex flex-col gap-4">
        <?php

        require './functions.php';

        foreach ($favorites as $movie) {
          $data = fetchMovieDetails($movie['title']);
          $movieID = fetchID($movie['title']);

          echo "
          <div class='w-full flex flex-row gap-4'>
          <div class='flex basis-[20%]'>
            <img src=" . $data['Poster'] . " class='object-fit h-full rounded-lg'>
          </div>
          <div class='flex flex-col basis-[80%] gap-2'>
            <h1 class='text-4xl font-[700] text-slate-700'>" . $data['Title'] . "</h1>
            <p class='font-[500] text-slate-500'>" . $data['Plot'] . "</p>
            <a href='./movie.php?movie=" . $movie['title'] . "' class='cursor-pointer max-w-[144px] text-center p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500'>View reviews</a>
          </div>
        </div>
          ";
        }

        ?>
      </div>

    <?php else : ?>
      <h1 class="text-2xl font-[700] text-slate-800">No favorites yet.</h1>
    <?php endif; ?>
  </main>
</body>

</html>