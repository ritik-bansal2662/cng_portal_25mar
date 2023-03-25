


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        <?php include '../dist/css/log.css' ?>
    </style>
</head>
<body>
        <div class='authenticate'>
            <h1>Login</h1>
            
            <form action='login.php' method='post' class='login-form'>
                <div class="txt_field">
                    <input required type="text" name='username' >
                    <span></span>
                    <label>Username</label>
                </div>
                <div class="txt_field">
                    <input required type='password' name='password' >
                    <span></span>
                    <label>Password</label>
                </div>
                <button type='submit'>Login</button>
            </form>
            <!-- <a class='signup-instead' href='signup.php'>Sign Up Instead</a> -->
        </div>
</body>
</html>