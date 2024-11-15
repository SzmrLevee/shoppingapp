<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belépés & Regisztráció</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="container" id="signup" style="display:none;">
      <h1 class="form-title">Regisztráció</h1>
      <form method="post" action="./app/register.php">
        <div class="input-group">
           <i class="fas fa-user"></i>
           <input type="text" name="fName" id="fName" placeholder="First Name" required>
           <label for="fName">Keresztnév</label>
        </div>
        <div class="input-group">
            <i class="fas fa-user"></i>
            <input type="text" name="lName" id="lName" placeholder="Last Name" required>
            <label for="lName">Vezetéknév</label>
        </div>
        <div class="input-group">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <label for="email">Email</label>
        </div>
        <div class="input-group">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <label for="password">Jelszó</label>
        </div>
       <input type="submit" class="btn" value="Sign Up" name="signUp">
      </form>
      <div class="links">
        <p>Már van fiókod?</p>
        <button id="signInButton">Belépés</button>
      </div>
    </div>

    <div class="container" id="signIn">
        <h1 class="form-title">Belépés</h1>
        <form method="post" action="./app/login.php">
          <div class="input-group">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" id="email" placeholder="Email" required>
              <label for="email">Email</label>
          </div>
          <div class="input-group">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" id="password" placeholder="Password" required>
              <label for="password">jelszó</label>
          </div>
         <input type="submit" class="btn" value="Sign In" name="signIn">
        </form>
        <div class="links">
          <p>Még nincsen fiókod?</p>
          <button id="signUpButton">Regisztráció</button>
        </div>
      </div>
      <script src="./js/script.js"></script>
</body>
</html>