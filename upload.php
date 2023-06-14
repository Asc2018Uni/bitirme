<!DOCTYPE html>
<html>

<head>
    <title>Fiş Ekle</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
        }

        h1 {
            text-align: center;
            color: #555;
            margin-top: 30px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 15px;
            background-color: #fff;
            z-index: 999;
        }

        #container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        #form {
            background-color: #fff;
            border-radius: 5px;
            padding: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #image {
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            position: relative;
        }

        #image img {
            max-width: 100%;
            max-height: 400px;
            margin-top: 20px;
            border-radius: 5px;
        }

        #form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
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

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Fiş Ekle</h1>

    <div id="container">
        <div id="form">
            <form action="main.php" method="post" enctype="multipart/form-data">
                <label for="fileInput">FOTOĞRAF:</label>
                <input type="file" id="fileInput" name="fileInput" accept="image/*" onchange="previewImage(event)">

                <div id="image">
                    <img id="preview">
                </div>

                <input type="submit" value="Yükle" onclick="return validateForm()">
                <p id="error" class="error-message"></p>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const previewImage = document.getElementById('preview');

            const file = fileInput.files[0];
            const reader = new FileReader();

            reader.addEventListener('load', function() {
                previewImage.src = reader.result;
            });

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function validateForm() {
            const fileInput = document.getElementById('fileInput');
            const errorMessage = document.getElementById('error');

            if (!fileInput.value) {
                errorMessage.textContent = 'Fotoğraf Yüklenmedi.';
                return false;
            }

            return true;
        }
    </script>
</body>

</html>
