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
    <script>
        module.exports = {
            theme: {
                container: {
                    center: true,
                },
            },
        }
    </script>
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
    <div class="grid grid-flow-row-dense md:grid-cols-3 gap-4" id="sitesList">
        @foreach($sites as $site)
            <a href="http://{{$site}}"
               class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
               target="_blank">{{$site}}</a>
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('search').addEventListener('input', function (e) {
            let value = e.target.value;
            showAllSites();
            if(value!==''){
                let sites = document.querySelectorAll(`#sitesList a:not([href*="${value}"])`);
                if (sites.length > 0) {
                    sites.forEach((item) => {
                        item.hidden = true;
                    });
                } else {
                    hideAllSites();
                }
            }
        });

        function showAllSites() {
            document.querySelectorAll(`#sitesList a`).forEach((item) => {
                item.hidden = false;
            });
        }

        function hideAllSites() {
            document.querySelectorAll(`#sitesList a`).forEach((item) => {
                item.hidden = true;
            });
        }
    });
</script>

</body>
</html>
