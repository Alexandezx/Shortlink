<?php
session_start();
$connect=mysqli_connect("localhost", "root", "","ShortLink");
if(isset($_POST['Login'])){
    $username =$_POST['Username'];
    $password =$_POST['Password'];
    $query="SELECT * FROM username WHERE Username='$username' AND Password='$password';";
    $gas=mysqli_query($connect, $query);
    if($gas->num_rows>0){
        header('Location:home.php');
    }
        else{
            echo'
            <script>
                alert("Login Gagal");
            </script>';
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            padding: 20px 0;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        input[type="text"],
        input[type="password"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Sign in to ShortLink</h1>
    <form method="POST">
        <input type="text" name="Username" placeholder="Username" required>
        <input type="password" name="Password" placeholder="Password" required>
        <input type="submit" name="Login" value="Login">
    </form>
</body>
</html>
