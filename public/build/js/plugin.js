
!function ($) {
    "use strict";
    // Keep the asset origin, deployment path and cache query when switching direction.
    function setThemeStylesheet(id, filename) {
        var link = document.getElementById(id);
        if (!link || !link.getAttribute('href')) return;

        var current = new URL(link.getAttribute('href'), document.baseURI);
        var target = new URL(current.href);
        target.pathname = target.pathname.replace(/[^/]+$/, filename);
        if (current.href !== target.href) {
            link.setAttribute('href', target.href);
        }
    }

    if (window.sessionStorage) {
        var alreadyVisited = sessionStorage.getItem("is_visited");
        if (alreadyVisited) {
            switch (alreadyVisited) {
                case "light-mode-switch":
                    document.documentElement.removeAttribute("dir");
                    setThemeStylesheet('bootstrap-style', 'bootstrap.min.css');
                    setThemeStylesheet('app-style', 'app.min.css');
                    document.documentElement.setAttribute("data-bs-theme", "light");
                    break;
                case "dark-mode-switch":
                    document.documentElement.removeAttribute("dir");
                    setThemeStylesheet('bootstrap-style', 'bootstrap.min.css');
                    setThemeStylesheet('app-style', 'app.min.css');
                    document.documentElement.setAttribute("data-bs-theme", "dark");
                    break;
                case "rtl-mode-switch":
                    setThemeStylesheet('bootstrap-style', 'bootstrap-rtl.min.css');
                    setThemeStylesheet('app-style', 'app-rtl.min.css');
                    document.documentElement.setAttribute("dir", "rtl");
                    document.documentElement.setAttribute("data-bs-theme", "light");
                    break;
                case "dark-rtl-mode-switch":
                    setThemeStylesheet('bootstrap-style', 'bootstrap-rtl.min.css');
                    setThemeStylesheet('app-style', 'app-rtl.min.css');
                    document.documentElement.setAttribute("dir", "rtl");
                    document.documentElement.setAttribute("data-bs-theme", "dark");
                    break;
                default:
                    console.log("Something wrong with the layout mode.");
            }
        }
    }
}(window.jQuery);
