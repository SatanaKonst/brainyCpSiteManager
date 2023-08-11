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
    <div class="columns-2 gap-3">
        <div>
            <input type="text"
                   id="search"
                   class="bg-gray-30 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Search">
        </div>
        <div>
            <a href="/?updateSiteList=Y"
               class="float-right text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700"
               title="Принудительно обновить">
                <svg class="h-3 inline-block text-gray-800 dark:text-white" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 1v5h-5M2 19v-5h5m10-4a8 8 0 0 1-14.947 3.97M1 10a8 8 0 0 1 14.947-3.97"/>
                </svg>
            </a>
        </div>

        <div>
            <button data-modal-target="addSite"
                    data-modal-toggle="addSite"
                    class="float-right bg-gray-800 hover:bg-gray-900 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow mr-2"
                    type="button"
                    title="Добавить сайт">
                <svg class="h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     width="20" height="20" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M10 5.757v8.486M5.757 10h8.486M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
            </button>
        </div>

    </div>
</div>
<div class="container mt-5 ml-10 mr-10">
    <div class="grid grid-flow-row-dense md:grid-cols-2 gap-4" id="sitesList">
        @foreach($sites as $index=>$site)
            <div
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                @if(!empty($site['domain']))
                    <a href="http://{{$site['domain']}}"
                       data-domain="{{$site['domain']}}"
                       data-auth-status="{{!empty($site['passDirs'])? 'Y' : 'N'}}"
                       target="_blank">
                        {{$site['domain']}}

                    </a>
                    <!-- Modal toggle -->
                    @if(!empty($site['passDirs']))
                        <button data-modal-target="passwdDir{{$index}}"
                                data-modal-toggle="passwdDir{{$index}}"
                                class="float-right bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
                                type="button"
                                title="Папки под паролем">
                            <svg class="h-3 text-gray-800 dark:text-black" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                <path
                                    d="M18 5H0v11a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V5Zm-7.258-2L9.092.8a2.009 2.009 0 0 0-1.6-.8H2.049a2 2 0 0 0-2 2v1h10.693Z"/>
                            </svg>
                        </button>
                    @endif
                    <!-- Main modal -->
                    <div id="passwdDir{{$index}}" tabindex="-1" aria-hidden="true"
                         class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative w-full max-w-2xl max-h-full">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <!-- Modal header -->
                                <div
                                    class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                        Папки под паролем
                                    </h3>
                                    <button type="button"
                                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                            data-modal-hide="passwdDir{{$index}}">
                                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6">
                                    <ul class="text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        @foreach($site['passDirs'] as $dirPath)
                                            <li class="w-full px-4 py-2 border-b border-gray-200 dark:border-gray-600">
                                                {{$dirPath}}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
{{--Добавление сайта--}}
<div id="addSite" tabindex="-1" aria-hidden="true"
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
            <!-- Modal header -->
            <div
                class="flex items-start justify-between p-4 border-b rounded-t dark:border-gray-600">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Добавление сайта
                </h3>
                <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="addSite">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                         xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-6 space-y-6">
                <form action="{{route('addSite')}}" method="post">
                    @csrf
                    <div
                        class="p-4 mb-4 text-sm text-white rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-white"
                        role="alert">
                        <span class="font-medium">Внимание!</span> Алиас www.site-name.f5-test.ru добавится
                        автоматически.
                        <br>
                        К имени базы добавляется префикс "{{$_ENV['PANEL_LOGIN']}}_"
                    </div>

                    <div class="grid mb-6 md:grid-cols-1">
                        <div class="mb-6">
                            <label for="domain"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Адрес
                                сайта</label>
                            <input type="text" id="domain" name="domain"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="newSite.f5-test.ru" required>
                        </div>
                        <div class="mb-6">
                            <label for="domain"
                                   class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Имя базы
                                данные</label>
                            <input type="text" id="domain" name="dbName" maxlength="7"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="myDB" required>
                        </div>
                        <div class="mb-6">
                            <label for="dbPass" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Пароль
                                для базы</label>
                            <input type="password" id="dbPass" name="dbPass"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="•••••••••" required>
                        </div>
                        {{--                        <div class="flex items-center mb-6">--}}
                        {{--                            <input checked id="setDirPassword" name="setDirPassword" type="checkbox" value="Y" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">--}}
                        {{--                            <label for="checked-checkbox" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Установить пароль на сайт</label>--}}
                        {{--                        </div>--}}
                        <button type="submit"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Добавить
                        </button>

                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.6/flowbite.min.js"></script>
@vite(['resources/css/app.css', 'resources/js/app.js']);
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let url = new URL(window.location.href);
        if (url.searchParams.has('addSite')) {
            let fullDbName = url.searchParams.get('fulldbname');
            let domain = url.searchParams.get('domain');
            let answer = confirm(`
                Новый сайт успешно добавлен.
                Адрес сайта: ${domain}
                Имя базы данных: ${fullDbName}
            `);
            url.searchParams.delete('fulldbname');
            url.searchParams.delete('domain');
            url.searchParams.delete('addSite');
            if (answer) {
                window.history.replaceState(null, '', url.toString());
            } else {
                window.location.href = url.toString();
            }
        }
    });
</script>

</body>
</html>
