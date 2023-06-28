import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('search').addEventListener('input', function (e) {
        let value = e.target.value;
        showAllSites();
        if (value !== '') {
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

    checkSiteAuth();

    function checkSiteAuth() {
        let checkerUrl = '/checkSiteAuth';

        let lockIcon = `
            <svg class="inline-block h-3 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.5 8V4.5a3.5 3.5 0 1 0-7 0V8M8 12v3M2 8h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"/>
            </svg>
        `;
        let unlockIcon = `
            <svg class="inline-block h-3 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.5 8V4.5a3.5 3.5 0 1 0-7 0V8M8 12.167v3M2 8h12a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1Z"/>
              </svg>
        `;
        document.querySelectorAll(`#sitesList a`).forEach((item, index) => {
            // setTimeout(() => {
            //     axios.get(
            //         `${checkerUrl}/${item.dataset.domain}`
            //     )
            //         .then(function (response) {
            //             if (response.data === true) {
            //                 item.innerHTML += lockIcon;
            //             } else {
            //                 item.innerHTML += unlockIcon;
            //                 item.classList.remove('bg-blue-700', 'dark:bg-blue-600')
            //                 item.classList.add('bg-red-700', 'dark:bg-red-600')
            //             }
            //         })
            //         .catch(function (error) {
            //             console.error(error);
            //         });
            // }, (index+1) * 1000);
        });
    }
});
