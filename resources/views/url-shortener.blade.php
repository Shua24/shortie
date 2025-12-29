<!-- resources/views/url-shortener.blade.php -->

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>URL Shortener</title>

    @vite('resources/css/app.css')
</head>
<body class="antialiased bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="max-w-xl w-full bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">URL Shortener</h1>
        <p class="text-center text-gray-600 mb-6">
            Enter your URL below to generate a shortened link.
        </p>

        <form id="urlForm" class="space-y-4">
            @csrf
            <input
                type="url"
                id="originalUrl"
                placeholder="https://example.com"
                required
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            <input
                type="number"
                id="expiresAfter"
                placeholder="Expiration (seconds, optional)"
                min="1"
                class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition-colors"
            >
                Shorten URL
            </button>
        </form>

        <div id="result" class="mt-4 text-center text-blue-600 font-semibold"></div>
    </div>

    <script>
        document.getElementById('urlForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const url = document.getElementById('originalUrl').value;
            const expiresAfter = document.getElementById('expiresAfter').value;
            const resultDiv = document.getElementById('result');
            resultDiv.textContent = 'Generating...';

            try {
                const payload = { url };
                if (expiresAfter) payload.expires_after = parseInt(expiresAfter);

                const response = await fetch('/api/generate-url', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();

                if (response.ok && data.result) {
                    resultDiv.innerHTML = `
                        Shortened URL:
                        <a href="${data.result}" target="_blank" class="underline mr-2">${data.result}</a>
                        <button id="copyBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-2 py-1 rounded">Copy</button>
                    `;

                    // Copy-to-clipboard logic
                    document.getElementById('copyBtn').addEventListener('click', () => {
                        navigator.clipboard.writeText(data.result)
                            .then(() => alert('Copied to clipboard!'))
                            .catch(err => alert('Failed to copy: ' + err));
                    });
                } else {
                    resultDiv.textContent = data.message || 'Failed to generate URL.';
                }
            } catch (err) {
                resultDiv.textContent = 'Error: ' + err.message;
            }
        });
    </script>
</body>
</html>

