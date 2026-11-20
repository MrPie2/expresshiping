<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <div class="container mt-5">
    <div class="row justify-content-center">
      <div class="col-md-4">
          <center><img src="../home/parcelsendloo-color.png" style="margin-top: 2cm; width: 250px"/><br><h1> Admin Panel</h1></center>
          
        <h3 class="text-center">Login</h3>
        <form id="loginForm">
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" plalceholder="Username" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div id="response" class="mt-3"></div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function () {
      $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        var username = $('#username').val();
        var password = $('#password').val();

        $.ajax({
          url:'process-login.php',
          type:'POST',
          data:{username: username,password: password
          },
          success: function (response) {
            if(response==1){
                window.location.href="Index.php";
            }else{
                $('.response').html("Username or Password incorrect");
            }
          }
        });
      });
    });
  </script>
</body>
</html>