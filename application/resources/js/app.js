import './bootstrap';

document.addEventListener('DOMContentLoaded', function () {
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
