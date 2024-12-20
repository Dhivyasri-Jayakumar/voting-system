(function () {
    // Prevent back navigation
    window.addEventListener('popstate', function () {
        history.pushState(null, '', location.href);
        console.log('Back navigation blocked.');
    });

    // Add history state on page load
    window.addEventListener('load', function () {
        history.pushState(null, '', location.href);
        window.onpopstate = function () {
            history.pushState(null, '', location.href);
        };
        console.log('Back navigation prevention initialized.');
    });

    // Completely remove beforeunload logic to avoid any prompt
    console.log('No beforeunload listener attached.');
})();
