<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Login</title>
                    <script src="https://cdn.tailwindcss.com"></script>
                </head>
                <body class="flex items-center justify-center min-h-screen bg-gray-100">
                    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
                        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">Login</h1>
                        <!-- Mostrar mensaje de error en la autentificación -->
                        @if ($errors->any())
                            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/login" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email:</label>
                                <input type="email" name="email" id="email" required value="{{old('email')}}"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                                <input type="password" name="password" id="password" required
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <button type="submit"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Login
                            </button>
                        </form>
                        <div class="text-center mt-4">
                            <a href="/register" class="text-indigo-600 hover:underline">Registrate</a>
                        </div>
                    </div>
                </body>
                </html>
