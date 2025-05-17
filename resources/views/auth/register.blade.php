<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Register</title>
            <link rel="shortcut icon" href={{ asset('favicon2.png') }}>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="flex items-center justify-center min-h-screen bg-gray-100">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
                <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Register</h1>
                <form action="/register" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Username:</label>
                        <input type="text" name="name" id="name" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                        <input type="email" name="email" id="email" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                        <input type="password" name="password" id="password" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password:</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Register
                    </button>
                </form>
                <div class="text-center mt-4">
                    <a href="/login" class="text-indigo-600 hover:underline">Inicia Sesion</a>
                </div>
            </div>
        </body>
        </html>
