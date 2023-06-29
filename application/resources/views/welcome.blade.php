<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="antialiased bg-gray-900">
<!-- Full-width fluid until the `md` breakpoint, then lock to container -->
<div class="container mt-5 ml-10 mr-10">
    <div class="columns-3 gap-3">
        <div>
            <input type="text"
                   id="search"
                   class="bg-gray-30 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Search">
        </div>

    </div>
</div>
<div class="container mt-5 ml-10 mr-10">
    <div class="grid grid-flow-row-dense md:grid-cols-2 gap-4" id="sitesList">
        @foreach($sites as $site)
            <div
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                @if(!empty($site['domain']))
                <a href="http://{{$site['domain']}}"
                   data-domain="{{$site['domain']}}"
                   data-auth-status="{{!empty($site['authStatus'])? $site['authStatus'] : ''}}"
                   target="_blank">
                    {{$site['domain']}}

                </a>
                <button type="button"  data-domain="{{$site['domain']}}"
                        class="js-update-auth-status float-right bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                    <svg class="h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 1v5h-5M2 19v-5h5m10-4a8 8 0 0 1-14.947 3.97M1 10a8 8 0 0 1 14.947-3.97"/>
                    </svg>
                </button>
                @endif
            </div>
        @endforeach
    </div>
</div>

@vite(['resources/css/app.css', 'resources/js/app.js'])

</body>
</html>
