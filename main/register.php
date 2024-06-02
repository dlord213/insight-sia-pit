<?php
session_start();

$connection = new PDO("pgsql:host=localhost;port=5432;dbname=insight", 'postgres', 'dlord213');

$error_message = "";

if (isset($_SESSION['isLoggedIn'])) {
  header('Location: ./index.php');
  exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  $full_name = $_POST['full_name'];
  $location = $_POST['location'];
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

  try {
    $connection->beginTransaction();

    $stmt = $connection->prepare("INSERT INTO _user(name, location, username, password) VALUES (?, ?, ?, ?)");

    $stmt->execute([$full_name, $location, $username, $password]);

    $connection->commit();
    header('Location: ./login.php');
    exit();
  } catch (PDOException $e) {
    $connection->rollBack();
    $e->getMessage();
  }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Insight / Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="./globals.css">
</head>

<body class="bg-slate-100 max-w-xl w-full mx-auto">
  <main class="flex flex-col h-[100vh] gap-2 justify-center">
    <a href="./index.php">
      <h1 class="text-4xl font-[700] text-slate-800">Insight</h1>
    </a>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-col gap-1">
      <input type="text" maxlength="16" name="full_name" placeholder="Full Name" class=" block p-4 text-slate-800 bg-white w-full border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
      <input type="text" maxlength="16" name="location" placeholder="Location" class=" block p-4 text-slate-800 bg-white w-full border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
      <input type="text" maxlength="16" name="username" placeholder="Username" class=" block p-4 text-slate-800 bg-white w-full border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
      <input type="password" maxlength="16" name="password" placeholder="Password" class=" block p-4 text-slate-800 bg-white w-full border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
      <input type="submit" value="Register" class="cursor-pointer  block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
      </div>
    </form>
  </main>
</body>

</html>