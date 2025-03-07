import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
    console.log('START')
    document.getElementById('search').addEventListener('input', function (e) {
        let value = e.target.value;
        showAllSites();
        if (value !== '') {
            let sites = document.querySelectorAll(`#sitesList a:not([href*="${value}"])`);
            if (sites.length > 0) {
                sites.forEach((item) => {
                    item.closest('div').hidden = true;
                });
            } else {
                hideAllSites();
            }
        }
    });

    document.getElementById('hostAcc').addEventListener('change', function (e) {
        let url = new URL(window.location);
        url.searchParams.set('currentUser', e.target.value);
        window.location = url.toString();
    });

    function showAllSites() {
        document.querySelectorAll(`#sitesList div`).forEach((item) => {
            item.hidden = false;
        });
    }

    function hideAllSites() {
        document.querySelectorAll(`#sitesList div`).forEach((item) => {
            item.hidden = true;
        });
    }
});
