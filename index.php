<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <script>
        async function encodeUrl() {
            const url = document.getElementById('url').value;

            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'encode', url })
            });

            const data = await response.json();
            document.getElementById('result').innerText = data.shortLink;
        }

        async function decodeUrl() {
            const shortLink = document.getElementById('shortLink').value;

            const response = await fetch('api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ action: 'decode', shortLink })
            });

            const data = await response.json();
            if (data.error) {
                alert(data.error);
            } else {
            document.getElementById('result').innerText = data.originalUrl;
            }
        }

    </script>
</head>
<body>
    <h2>URL Shortener</h2>

    <h3>Shorten URL:</h3>
    <input type="text" id="url" placeholder="Enter URL to shorten">
    <button onclick="encodeUrl()">Shorten</button>

    <h3>Decode Shortened URL:</h3>
    <input type="text" id="shortLink" placeholder="Enter short URL to decode">
    <button onclick="decodeUrl()">Decode</button>

    <h3>Result:</h3>
    <p id="result"></p>
</body>
</html>
