<?php
// Establish a connection to the MySQL database
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'project';

$cnx = mysqli_connect($host, $user, $password, $database);

if (!$cnx) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query the database to get the last uploaded data
$query = "SELECT * FROM result ORDER BY id DESC LIMIT 1";
$result = mysqli_query($cnx, $query);

// Check if the query was successful
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Get the values from the database row
    $aciklama = $row['aciklama'];
    $saat = $row['saat'];
    $tarih = $row['tarih'];
    $odeme = $row['odeme'];
    $toplam = $row['toplam'];
    $img_link = $row['img_link'];

    // Close the database connection
    mysqli_close($cnx);
} else {
    echo "No data found in the result table.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Fiş Tarayıcı</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #555;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            padding: 15px;
            background-color: white;
            z-index: 999;
            transition: top 0.3s ease-in-out;
        }

        #container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding-top: 60px;
            margin-top: 80px;
            /* Adjust the margin value as needed */
        }

        #form {
            margin-bottom: 20px;
        }

        #image {
            text-align: center;
            margin-top: 20px;
            width: 100%;
            max-width: 600px;
            /* Set a maximum width if desired */
        }

        #image img {
            max-width: 100%;
            max-height: 400px;
            border-radius: 5px;
        }

        #form input[type="text"],
        #form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        #form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #444;
        }

        #form label[for="fileInput"] {
            display: inline-block;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #form label[for="fileInput"]:hover {
            background-color: #45a049;
        }

        #form input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        #extractButton {
            width: 25%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-bottom: 10px;
            font-weight: bold;
        }

        * {
            box-sizing: border-box;
        }

        .img-magnifier-container {
            position: relative;
        }

        .img-magnifier-glass {
            position: absolute;
            border: 3px solid #000;
            border-radius: 50%;
            cursor: none;
            /*Set the size of the magnifier glass:*/
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <h1>Fiş Tarayıcı</h1>

    <div id="container">
        <div id="form">
            <form action="update.php" method="post">
                <label for="explain">AÇIKLAMA:</label>
                <input type="text" id="explain" name="explain" placeholder="AÇIKLAMA (Maksimum 200 Karakter)">

                <label for="date">TARİH:</label>
                <input type="text" id="date" name="date" placeholder="TARİH">

                <label for="time">SAAT:</label>
                <input type="text" id="time" name="time" placeholder="SAAT">

                <label for="payment">ÖDEME:</label>
                <input type="text" id="payment" name="payment" placeholder="ÖDEME">

                <label for="total">TOPLAM:</label>
                <input type="text" id="total" name="total" placeholder="TOPLAM">

                <input type="submit" value="Gönder">
            </form>
        </div>

        <div id="image" class="img-magnifier-container">
            <?php
            echo "<img id='myimage' src='" . $img_link . "' width='600' height='400' />";
            ?>
        </div>
    </div>

    <script>
        function magnify(imgID, zoom) {
            var img, glass, w, h, bw;
            img = document.getElementById(imgID);
            /*create magnifier glass:*/
            glass = document.createElement("DIV");
            glass.setAttribute("class", "img-magnifier-glass");
            /*insert magnifier glass:*/
            img.parentElement.insertBefore(glass, img);
            /*set background properties for the magnifier glass:*/
            glass.style.backgroundImage = "url('" + img.src + "')";
            glass.style.backgroundRepeat = "no-repeat";
            glass.style.backgroundSize = (img.width * zoom) + "px " + (img.height * zoom) + "px";
            bw = 3;
            w = glass.offsetWidth / 2;
            h = glass.offsetHeight / 2;
            /*execute a function when someone moves the magnifier glass over the image:*/
            glass.addEventListener("mousemove", moveMagnifier);
            img.addEventListener("mousemove", moveMagnifier);
            /*and also for touch screens:*/
            glass.addEventListener("touchmove", moveMagnifier);
            img.addEventListener("touchmove", moveMagnifier);

            function moveMagnifier(e) {
                var pos, x, y;
                /*prevent any other actions that may occur when moving over the image*/
                e.preventDefault();
                /*get the cursor's x and y positions:*/
                pos = getCursorPos(e);
                x = pos.x;
                y = pos.y;
                /*prevent the magnifier glass from being positioned outside the image:*/
                if (x > img.width - (w / zoom)) {
                    x = img.width - (w / zoom);
                }
                if (x < w / zoom) {
                    x = w / zoom;
                }
                if (y > img.height - (h / zoom)) {
                    y = img.height - (h / zoom);
                }
                if (y < h / zoom) {
                    y = h / zoom;
                }
                /*set the position of the magnifier glass:*/
                glass.style.left = (x - w) + "px";
                glass.style.top = (y - h) + "px";
                /*display what the magnifier glass "sees":*/
                glass.style.backgroundPosition = "-" + ((x * zoom) - w + bw) + "px -" + ((y * zoom) - h + bw) + "px";
            }

            function getCursorPos(e) {
                var a, x = 0,
                    y = 0;
                e = e || window.event;
                /*get the x and y positions of the image:*/
                a = img.getBoundingClientRect();
                /*calculate the cursor's x and y coordinates, relative to the image:*/
                x = e.pageX - a.left;
                y = e.pageY - a.top;
                /*consider any page scrolling:*/
                x = x - window.pageXOffset;
                y = y - window.pageYOffset;
                return {
                    x: x,
                    y: y
                };
            }
        }
        /*initiate the magnifier glass:*/
        magnify("myimage", 3);
        // Set the values retrieved from the database to the form fields
        document.getElementById('explain').value = '<?php echo $aciklama; ?>';
        document.getElementById('date').value = '<?php echo $tarih; ?>';
        document.getElementById('time').value = '<?php echo $saat; ?>';
        document.getElementById('payment').value = '<?php echo $odeme; ?>';
        document.getElementById('total').value = '<?php echo $toplam; ?>';

        // Hide or show the header based on the scroll position
        var prevScrollPos = window.pageYOffset;
        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;
            if (prevScrollPos > currentScrollPos) {
                document.querySelector("h1").style.top = "0";
            } else {
                document.querySelector("h1").style.top = "-100px";
            }
            prevScrollPos = currentScrollPos;
        };
        // Hide or show the header based on the scroll position
        var prevScrollPos = window.pageYOffset;
        window.onscroll = function() {
            var currentScrollPos = window.pageYOffset;
            if (prevScrollPos > currentScrollPos) {
                document.querySelector("h1").style.top = "0";
            } else {
                document.querySelector("h1").style.top = "-100px";
            }
            prevScrollPos = currentScrollPos;
        };

        // Adjust the initial position of the header based on the scroll position
        window.onload = function() {
            var currentScrollPos = window.pageYOffset;
            if (currentScrollPos > 0) {
                document.querySelector("h1").style.top = "-100px";
            }
        };
    </script>
</body>

</html>