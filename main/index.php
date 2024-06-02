<?php
session_start();

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insight</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./globals.css">
</head>

<body class="bg-slate-100 max-w-xl w-full mx-auto">
  <main class="flex flex-col h-[100vh] gap-2 justify-center">
    <div>
      <h1 class="text-4xl font-[700] text-slate-800">Insight</h1>
      <p class="text-slate-600">See what people think about your favorite movie!</p>
    </div>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-row gap-2">
      <input type="hidden" name="form_type" value="search">
      <input type="text" name="movie_name" placeholder="Search by title" class="mt-1 block p-4 text-slate-800 bg-white w-full border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
      <input type="submit" value="Search" class="cursor-pointer mt-1 block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
      </div>
    </form>
    <div class="flex flex-row gap-2">
      <?php if (!isset($_SESSION['isLoggedIn'])) : ?>
        <a class="cursor-pointer mt-1 w-full text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" href="./login.php">Login</a>
        <a class="cursor-pointer mt-1 w-full text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" href="./register.php">Register</a>
      <?php else : ?>
        <a class="cursor-pointer mt-1 w-full text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" href="./favorites.php">View your favorites</a>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-row gap-2">
          <input type="hidden" name="form_type" value="logout">
          <input type="submit" value="Logout" class="cursor-pointer mt-1 w-full text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
        </form>
      <?php endif; ?>
    </div>
  </main>
</body>

</html>