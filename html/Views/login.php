<!DOCTYPE html>
<html lang="ja">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
 <head>
   <meta charset="utf-8">
   <title>Login</title>
    <link rel="stylesheet" href="css/login.css">
 </head>
 <body>
  <div id="form">
    <form  action="login" method="post">
        <div class="form-item">
          <input type="text" name="uid" id="uid" class="form-style" placeholder="User ID"/>
          <label for="text">User ID</label>
        </div>
        <div class="form-item">
          <input type="password" name="password" id="password" class="form-style" placeholder="Password"/>
          <label for="password">Password</label>
        </div>
        <div class="form-item">
          <input type="submit" class="login pull-right" value="LOG IN"/>
        </div>
    </form>
  </div>
</body>
</html>
