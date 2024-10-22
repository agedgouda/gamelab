<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Game Lab</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        <div class="bg-gray-50 text-black dark:bg-black dark:text-white/50">
            <!--<img id="background" class="absolute -left-20 min-h-screen bg-cover bg-center bg-no-repeat" src="/img/his.jpg" />-->
            <div class="relative w-full max-w-2xl px-10 lg:max-w-7xl mx-auto">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]" ><path d="M0 0h512v512H0z" fill="red" fill-opacity="1"></path><g class="" transform="translate(0,0)" style=""><path d="M247 16v23.2C134.4 43.81 43.81 134.4 39.2 247H16v18h23.2c4.61 112.6 95.2 203.2 207.8 207.8V496h18v-23.2c112.6-4.6 203.2-95.2 207.8-207.8H496v-18h-23.2C468.2 134.4 377.6 43.81 265 39.2V16h-18zm0 41.21V80h18V57.21C367.8 61.78 450.2 144.2 454.8 247H432v18h22.8c-4.6 102.8-87 185.2-189.8 189.8V432h-18v22.8c-102.8-4.6-185.22-87-189.79-189.8H80v-18H57.21C61.78 144.2 144.2 61.78 247 57.21zm8.9 38.12c-5 0-9.5 1.1-12.9 2.9L126.8 165.3c-7.3 4.1-7.3 10.9 0 15.2L243 247.4c7.2 4.3 18.5 4.3 25.7 0l115.9-66.9c7.4-4.3 7.4-11.1 0-15.2L268.7 98.23c-3.4-1.8-8.3-2.9-12.8-2.9zm-89 62.57c6 .1 11.7 1.6 16 4.1 8 5.7 7.3 14.1-1.5 19.4-9 5.2-23.1 5.6-32.7.8-14.9-9.3-3.4-24.7 18.2-24.3zm178.6.1h2c6 .2 11.7 1.8 15.9 4.2 8.3 5.7 7.7 14.3-1.2 19.6-9.1 5.3-23.4 5.6-33 .7-15-9-4.5-24.1 16.3-24.5zm-89 0c21.4.2 31.8 15.5 16.8 24.5-9.3 6.2-25.2 6.4-35.1.6-9.8-5.8-9.6-15.3.6-20.9 4.7-2.7 11.1-4.3 17.7-4.2zm-141 41c-4.5 0-7.5 3-7.5 9.2v119.7c0 8.4 5.8 18.3 13.2 22.6l111.4 64.4c7.2 4.1 12.9.7 12.9-7.6V287.6c0-8.3-5.7-18.4-12.9-22.5l-111.5-64.5c-2.2-1.1-4.1-1.5-5.6-1.6zm281.3 0c-1.6.1-3.7.5-5.8 1.6l-111.5 64.5c-7.2 4.1-12.9 14.2-12.9 22.5v119.7c0 8.3 5.7 11.7 12.9 7.6L391 350.5c7-4.3 13-14.2 13-22.6V208.2c0-6.2-3-9.2-7.2-9.2zm-185 65.5c11.2.4 24.7 17.3 24.5 31.5.4 11-7.4 15.5-17.2 9.9-9.7-5.7-17.5-19.4-16.9-29.8 0-6.8 3.2-11.2 8.5-11.6h1.1zm130.9 21.8h1.1c5.2.4 8.5 4.8 8.5 11.5-.1 10.5-7.7 23.3-17.1 28.8-9.5 5.5-17.1 1.5-17.2-8.9-.1-14.2 13.5-31.3 24.7-31.4zm-216.9 22.5c11.4-.5 25.5 16.8 25.5 31.3.4 11.1-7.4 15.6-17.2 10.1-9.7-5.7-17.5-19.3-17-29.8 0-6.9 3.3-11.3 8.7-11.6z" fill="#fff" fill-opacity="1"></path></g></svg>
                    </div>
                    @if (Route::has('login'))
                        <livewire:welcome.navigation />
                    @endif
                </header>

                <main class="mt-6">
                        <div
                            id="docs-card"
                            class="flex flex-col items-start gap-6 overflow-hidden rounded-lg bg-white p-4 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05]
                             transition duration-300 md:row-span-3 
                              dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#FF2D20]"
                        >
                            @livewire('list-events')
                            @if(request()->routeIs('view-event'))
                            @livewire('view-event',['eventId' => $eventId])
                            @endif
                        </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Footer Text
                </footer>
            </div>
        </div>
    </body>
</html>
