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

    document.querySelectorAll('.js-update-auth-status').forEach((item) => {
        item.addEventListener('click', (event) => {
            let domain = item.dataset.domain;
            console.log(domain)
            checkSiteAuth(
                domain,
                (response) => {
                    console.log(response)
                },
                (error) => {
                    console.error(error)
                }
            );

        });
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


    function checkSiteAuth(domain, callback, errorCallback) {
        let checkerUrl = '/checkSiteAuth';

        axios.get(
            `${checkerUrl}/${domain}`,
            {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }
        )
            .then(function (response) {
                if (response.data === true) {
                    callback(response.data);
                } else {
                    errorCallback(response);
                }
            })
            .catch(function (error) {
                console.error(error);
            });
    }
});
