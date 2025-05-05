<nav class="bg-gradient-to-r from-blue-600 to-blue-500 w-full border-b border-gray-800">
                        <div class="flex h-16 items-center">
                            <!-- Parte Izquierda -->
                            <div class="flex h-full pr-24 pl-24 pt-4 text-center ">
                                <p class="text-white font-semibold text-2xl">Tablas</p>
                            </div>
                            <!-- Parte Derecha -->
                            <div class="flex justify-end w-full pr-4 mr-12">
                                <div class="hidden md:block">
                                    <div class="flex space-x-6">
                                        <a href="/"
                                           class="bg-white hover:bg-green-500 text-green-700 font-semibold hover:text-white py-2 px-4 border border-green-500 hover:border-transparent rounded">
                                            Home
                                        </a>
                                        <form action="/logout" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="bg-white hover:bg-red-700 text-red-600 font-semibold hover:text-white py-2 px-4 border border-red-600 hover:border-transparent rounded">
                                                Log out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </nav>
