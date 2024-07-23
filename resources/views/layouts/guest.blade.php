<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50">
        <div x-data="{ mobileNavOpen: false }">
        <nav class="relative bg-white py-3 shadow">
            <div class="container mx-auto px-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="#" class="inline-block mr-10 text-indigo-600 font-bold">
                            QR code generator
                        </a>
                        <ul class="hidden lg:flex items-center gap-10">
                            <li><a href="{{route('qr.index')}}" class="text-gray-500 text-sm font-medium hover:text-gray-900 transition duration-200">All codes</a></li>
                            <li><a href="{{route('qr.create')}}" class="text-gray-500 text-sm font-medium hover:text-gray-900 transition duration-200">Generate</a></li>
                        </ul>
                    </div>
                    <div class="hidden lg:flex gap-10">
                        <a href="#" class="group">
                            <div class="flex items-center flex-wrap gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none">
                                    <path d="M19 7H16V6C16 4.93913 15.5786 3.92172 14.8284 3.17157C14.0783 2.42143 13.0609 2 12 2C10.9391 2 9.92172 2.42143 9.17157 3.17157C8.42143 3.92172 8 4.93913 8 6V7H5C4.73478 7 4.48043 7.10536 4.29289 7.29289C4.10536 7.48043 4 7.73478 4 8V19C4 19.7956 4.31607 20.5587 4.87868 21.1213C5.44129 21.6839 6.20435 22 7 22H17C17.7956 22 18.5587 21.6839 19.1213 21.1213C19.6839 20.5587 20 19.7956 20 19V8C20 7.73478 19.8946 7.48043 19.7071 7.29289C19.5196 7.10536 19.2652 7 19 7ZM10 6C10 5.46957 10.2107 4.96086 10.5858 4.58579C10.9609 4.21071 11.4696 4 12 4C12.5304 4 13.0391 4.21071 13.4142 4.58579C13.7893 4.96086 14 5.46957 14 6V7H10V6ZM18 19C18 19.2652 17.8946 19.5196 17.7071 19.7071C17.5196 19.8946 17.2652 20 17 20H7C6.73478 20 6.48043 19.8946 6.29289 19.7071C6.10536 19.5196 6 19.2652 6 19V9H8V10C8 10.2652 8.10536 10.5196 8.29289 10.7071C8.48043 10.8946 8.73478 11 9 11C9.26522 11 9.51957 10.8946 9.70711 10.7071C9.89464 10.5196 10 10.2652 10 10V9H14V10C14 10.2652 14.1054 10.5196 14.2929 10.7071C14.4804 10.8946 14.7348 11 15 11C15.2652 11 15.5196 10.8946 15.7071 10.7071C15.8946 10.5196 16 10.2652 16 10V9H18V19Z" fill="black"></path>
                                </svg>
                            </div>
                        </a>
                        <a href="#" class="group">
                            <div class="flex items-center flex-wrap gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none">
                                    <path d="M15.7105 12.71C16.6909 11.9387 17.4065 10.8809 17.7577 9.68394C18.109 8.48697 18.0784 7.21027 17.6703 6.03147C17.2621 4.85267 16.4967 3.83039 15.4806 3.10686C14.4644 2.38332 13.2479 1.99451 12.0005 1.99451C10.753 1.99451 9.5366 2.38332 8.52041 3.10686C7.50423 3.83039 6.73883 4.85267 6.3307 6.03147C5.92257 7.21027 5.892 8.48697 6.24325 9.68394C6.59449 10.8809 7.31009 11.9387 8.29048 12.71C6.61056 13.383 5.14477 14.4994 4.04938 15.9399C2.95398 17.3805 2.27005 19.0913 2.07048 20.89C2.05604 21.0213 2.0676 21.1542 2.10451 21.2811C2.14142 21.4079 2.20295 21.5263 2.2856 21.6293C2.4525 21.8375 2.69527 21.9708 2.96049 22C3.2257 22.0292 3.49164 21.9518 3.69981 21.7849C3.90798 21.618 4.04131 21.3752 4.07049 21.11C4.29007 19.1552 5.22217 17.3498 6.6887 16.0388C8.15524 14.7278 10.0534 14.003 12.0205 14.003C13.9876 14.003 15.8857 14.7278 17.3523 16.0388C18.8188 17.3498 19.7509 19.1552 19.9705 21.11C19.9977 21.3557 20.1149 21.5827 20.2996 21.747C20.4843 21.9114 20.7233 22.0015 20.9705 22H21.0805C21.3426 21.9698 21.5822 21.8373 21.747 21.6313C21.9119 21.4252 21.9886 21.1624 21.9605 20.9C21.76 19.0962 21.0724 17.381 19.9713 15.9382C18.8703 14.4954 17.3974 13.3795 15.7105 12.71ZM12.0005 12C11.2094 12 10.436 11.7654 9.7782 11.3259C9.12041 10.8864 8.60772 10.2616 8.30497 9.53074C8.00222 8.79983 7.923 7.99557 8.07734 7.21964C8.23168 6.44372 8.61265 5.73099 9.17206 5.17158C9.73147 4.61217 10.4442 4.2312 11.2201 4.07686C11.996 3.92252 12.8003 4.00173 13.5312 4.30448C14.2621 4.60724 14.8868 5.11993 15.3264 5.77772C15.7659 6.43552 16.0005 7.20888 16.0005 8C16.0005 9.06087 15.5791 10.0783 14.8289 10.8284C14.0788 11.5786 13.0614 12 12.0005 12Z" fill="black"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                    <button x-on:click="mobileNavOpen = !mobileNavOpen" class="lg:hidden">
                        <svg class="text-indigo-600" width="51" height="51" viewbox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="56" height="56" rx="28" fill="currentColor"></rect>
                            <path d="M37 32H19M37 24H19" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>
        <div x-show="mobileNavOpen" class="navbar-menu fixed top-0 left-0 bottom-0 w-5/6 max-w-xs z-50" style="display: none;">
            <div x-on:click="mobileNavOpen = !mobileNavOpen" class="navbar-menu fixed inset-0 bg-black opacity-20"></div>
            <nav class="relative p-8 w-full h-full bg-white overflow-y-auto">
                <div class="flex items-center justify-between">
                    <a href="#" class="inline-block text-indigo-600 font-bold">
                        Card Quest
                    </a>
                    <button x-on:click="mobileNavOpen = !mobileNavOpen">
                        <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 18L18 6M6 6L18 18" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
                <ul class="flex flex-col gap-12 py-12">
                    <li><a href="{{route('qr.index')}}" class="text-sm font-medium">All</a></li>
                    <li><a href="{{route('qr.create')}}" class="text-sm font-medium">Generate</a></li>
                </ul>
                <div class="flex flex-col gap-10">
                    <a href="#" class="group">
                        <div class="flex items-center flex-wrap gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none">
                                <path d="M19 7H16V6C16 4.93913 15.5786 3.92172 14.8284 3.17157C14.0783 2.42143 13.0609 2 12 2C10.9391 2 9.92172 2.42143 9.17157 3.17157C8.42143 3.92172 8 4.93913 8 6V7H5C4.73478 7 4.48043 7.10536 4.29289 7.29289C4.10536 7.48043 4 7.73478 4 8V19C4 19.7956 4.31607 20.5587 4.87868 21.1213C5.44129 21.6839 6.20435 22 7 22H17C17.7956 22 18.5587 21.6839 19.1213 21.1213C19.6839 20.5587 20 19.7956 20 19V8C20 7.73478 19.8946 7.48043 19.7071 7.29289C19.5196 7.10536 19.2652 7 19 7ZM10 6C10 5.46957 10.2107 4.96086 10.5858 4.58579C10.9609 4.21071 11.4696 4 12 4C12.5304 4 13.0391 4.21071 13.4142 4.58579C13.7893 4.96086 14 5.46957 14 6V7H10V6ZM18 19C18 19.2652 17.8946 19.5196 17.7071 19.7071C17.5196 19.8946 17.2652 20 17 20H7C6.73478 20 6.48043 19.8946 6.29289 19.7071C6.10536 19.5196 6 19.2652 6 19V9H8V10C8 10.2652 8.10536 10.5196 8.29289 10.7071C8.48043 10.8946 8.73478 11 9 11C9.26522 11 9.51957 10.8946 9.70711 10.7071C9.89464 10.5196 10 10.2652 10 10V9H14V10C14 10.2652 14.1054 10.5196 14.2929 10.7071C14.4804 10.8946 14.7348 11 15 11C15.2652 11 15.5196 10.8946 15.7071 10.7071C15.8946 10.5196 16 10.2652 16 10V9H18V19Z" fill="black"></path>
                            </svg>
                            <span class="text-sm text-gray-500 group-hover:text-gray-900 transition duration-200">Cart(0)</span>
                        </div>
                    </a>
                    <a href="#" class="group">
                        <div class="flex items-center flex-wrap gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewbox="0 0 24 24" fill="none">
                                <path d="M15.7105 12.71C16.6909 11.9387 17.4065 10.8809 17.7577 9.68394C18.109 8.48697 18.0784 7.21027 17.6703 6.03147C17.2621 4.85267 16.4967 3.83039 15.4806 3.10686C14.4644 2.38332 13.2479 1.99451 12.0005 1.99451C10.753 1.99451 9.5366 2.38332 8.52041 3.10686C7.50423 3.83039 6.73883 4.85267 6.3307 6.03147C5.92257 7.21027 5.892 8.48697 6.24325 9.68394C6.59449 10.8809 7.31009 11.9387 8.29048 12.71C6.61056 13.383 5.14477 14.4994 4.04938 15.9399C2.95398 17.3805 2.27005 19.0913 2.07048 20.89C2.05604 21.0213 2.0676 21.1542 2.10451 21.2811C2.14142 21.4079 2.20295 21.5263 2.2856 21.6293C2.4525 21.8375 2.69527 21.9708 2.96049 22C3.2257 22.0292 3.49164 21.9518 3.69981 21.7849C3.90798 21.618 4.04131 21.3752 4.07049 21.11C4.29007 19.1552 5.22217 17.3498 6.6887 16.0388C8.15524 14.7278 10.0534 14.003 12.0205 14.003C13.9876 14.003 15.8857 14.7278 17.3523 16.0388C18.8188 17.3498 19.7509 19.1552 19.9705 21.11C19.9977 21.3557 20.1149 21.5827 20.2996 21.747C20.4843 21.9114 20.7233 22.0015 20.9705 22H21.0805C21.3426 21.9698 21.5822 21.8373 21.747 21.6313C21.9119 21.4252 21.9886 21.1624 21.9605 20.9C21.76 19.0962 21.0724 17.381 19.9713 15.9382C18.8703 14.4954 17.3974 13.3795 15.7105 12.71ZM12.0005 12C11.2094 12 10.436 11.7654 9.7782 11.3259C9.12041 10.8864 8.60772 10.2616 8.30497 9.53074C8.00222 8.79983 7.923 7.99557 8.07734 7.21964C8.23168 6.44372 8.61265 5.73099 9.17206 5.17158C9.73147 4.61217 10.4442 4.2312 11.2201 4.07686C11.996 3.92252 12.8003 4.00173 13.5312 4.30448C14.2621 4.60724 14.8868 5.11993 15.3264 5.77772C15.7659 6.43552 16.0005 7.20888 16.0005 8C16.0005 9.06087 15.5791 10.0783 14.8289 10.8284C14.0788 11.5786 13.0614 12 12.0005 12Z" fill="black"></path>
                            </svg>
                            <span class="text-sm text-gray-500 group-hover:text-gray-900 transition duration-200">Account</span>
                        </div>
                    </a>
                </div>
            </nav>
        </div>
    </div>
        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </body>
</html>
