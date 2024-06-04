<header class="flex flex-col justify-between py-4 gap-2">
  <div class="flex flex-row justify-between">
    <div>
      <a href="./index.php">
        <h1 class="text-4xl font-[700] text-slate-800">Insight</h1>
      </a>
      <p class="text-slate-600">See what people think about your favorite movie!</p>
    </div>
    <?php if (isset($_SESSION['isLoggedIn'])) : ?>
      <div class="flex flex-row justify-end items-center gap-2">
        <a href="./favorites.php" class="">
          <div class="flex flex-col text-right">
            <p class="text-slate-700 font-[700]"><?php echo $_SESSION['username'] ?></p>
            <p class="text-slate-500 font-[500]"><?php echo $_SESSION['name'] ?></p>
          </div>
        </a>
        <a href="./favorites.php">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="40" height="40" class="text-slate-700 hover:text-slate-500 hover:scale-[1.1] transition-all delay-0 duration-500 ease"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
            <path d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" fill="currentColor" />
          </svg>
        </a>
      </div>
    <?php endif; ?>
  </div>
  <div class="flex flex-row justify-between">
    <div class="flex flex-col gap-2">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-row gap-2">
        <input type="hidden" name="form_type" value="search">
        <input type="text" name="movie_name" placeholder="Search by title" class="block p-4 text-slate-800 bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800" required />
        <input type="submit" value="Search" class="cursor-pointer block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
      </form>
    </div>
    <?php if (isset($_SESSION['isLoggedIn'])) : ?>
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="flex flex-row justify-end">
        <input type="hidden" name="form_type" value="logout">
        <input type="submit" value="Logout" class="cursor-pointer text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" />
      </form>
    <?php else : ?>
      <div class="flex flex-row gap-1">
        <a class="cursor-pointer text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" href="./login.php">Login</a>
        <a class="cursor-pointer text-center block p-4 text-slate-800 hover:bg-slate-800 hover:text-slate-50 transition-all delay-0 duration-250 ease-in-out bg-white border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:border-slate-800 focus:ring-1 focus:ring-slate-800 invalid:border-red-500 invalid:text-red-600 focus:invalid:border-red-500 focus:invalid:ring-red-500" href="./register.php">Register</a>
      </div>
    <?php endif; ?>
  </div>
</header>