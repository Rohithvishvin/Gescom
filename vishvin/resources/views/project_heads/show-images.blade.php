<!-- resources/views/show_image.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Image</title>
    <style>
        img {
            width: 400px;
            height: auto;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Image Display</h1>

    <div>
        <img src="{{ asset($file) }}" alt="Image">
    </div>
</body>
</html>
