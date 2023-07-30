<!DOCTYPE html>
<html>
<head>
    <title>ShortLink - Home</title>
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

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"] {
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
<body style="font-family: Arial, sans-serif; background-color: #f2f2f2; margin: 0; padding: 20px;">
    <h1 style="text-align: center;">ShortLink - URL Shortener</h1>
    <form method="post" action="" style="max-width: 400px; margin: 0 auto; padding: 20px; background-color: #fff; border-radius: 5px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
        <label for="original_url" style="font-weight: bold; display: block; margin-bottom: 5px;">Enter your long URL:</label>
        <input type="text" name="original_url" id="original_url" required style="display: block; width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">
        <label for="short_code" style="font-weight: bold; display: block; margin-bottom: 5px;">Custom short slug (optional):</label>
        <input type="text" name="short_code" id="short_code" style="display: block; width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 16px;">
        <input type="submit" value="Shorten" style="display: block; width: 100%; padding: 10px; background-color: #4CAF50; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">
    </form>
</body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $original_url = $_POST['original_url'];
        $short_code = $_POST['short_code'];

        if (filter_var($original_url, FILTER_VALIDATE_URL)) {
            if (empty($short_code)) {
                $short_code = generateShortCode();
            }

            $connect = mysqli_connect("localhost", "root", "", "ShortLink");

            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $query = "SELECT * FROM short_urls WHERE short_code = '$short_code'";
            $result = mysqli_query($connect, $query);

            if (mysqli_num_rows($result) > 0) {
                echo "Custom short slug is already in use. Please choose a different one.";
            } else {
                $query = "INSERT INTO short_urls (original_url, short_code, visit_count) VALUES ('$original_url', '$short_code', 0)";
                $result = mysqli_query($connect, $query);

                if ($result) {
                    $shortened_url = "http://your_web_domain.com/$short_code"; 
                    echo "<p>Shortened URL: <a href='$shortened_url'>$shortened_url</a></p>";
                } else {
                    echo "Error saving data: " . mysqli_error($connect);
                }
            }

            mysqli_close($connect);
        } else {
            echo "Invalid URL. Please enter a valid URL.";
        }
    }

    function generateShortCode() {
        $length = 6;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $short_code = '';

        for ($i = 0; $i < $length; $i++) {
            $random_index = mt_rand(0, strlen($characters) - 1);
            $short_code .= $characters[$random_index];
        }

        return $short_code;
    }
    ?>

    <?php
    $connect = mysqli_connect("localhost", "root", "", "ShortLink");

    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $query = "SELECT * FROM short_urls ORDER BY id DESC LIMIT 1"; 
    $result = mysqli_query($connect, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $short_code = $row['short_code'];
        $original_url = $row['original_url'];
        $visit_count = $row['visit_count'];

        echo "<h2>Latest Shortened URL:</h2>";
        echo "<p>Short URL: <a href='http://your_web_domain.com/$short_code' target='_blank'>http://your_web_domain.com/$short_code</a></p>";
        echo "<ul><li>Original URL: <a href='$original_url' target='_blank'>$original_url</a></li></ul>";
        echo "<li>Visits: $visit_count</li>";
    } else {
        echo "<h2>Latest Shortened URL:</h2>";
        echo "<p>No shortened URLs found.</p>";
    }

    mysqli_close($connect);
    ?>
</body>
</html>
