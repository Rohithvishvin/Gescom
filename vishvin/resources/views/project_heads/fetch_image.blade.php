<!-- resources/views/fetch_image.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Image</title>
</head>
<body>
    <h1>Fetch Image by Filename</h1>

    @if(session('error'))
        <div style="color: red;">{{ session('error') }}</div>
    @endif

    <form action="/fetch-image" method="POST">
        @csrf
        <label for="filename">Enter Image Filename (e.g., BOq68390800000.jpg):</label><br>
        <input type="text" id="filename" name="filename" required><br><br>
        <button type="submit">Fetch Image</button>
    </form>
</body>
</html>
