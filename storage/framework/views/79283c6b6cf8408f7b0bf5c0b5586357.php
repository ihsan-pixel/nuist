<script>
    document.addEventListener('DOMContentLoaded', function () {
        var loader = document.getElementById('authLoader');

        window.MobileAuthLoader = {
            show: function () {
                if (!loader) return;
                loader.classList.add('is-visible');
                loader.setAttribute('aria-hidden', 'false');
            },
            hide: function () {
                if (!loader) return;
                loader.classList.remove('is-visible');
                loader.setAttribute('aria-hidden', 'true');
            }
        };

        window.MobileAuthLoader.hide();

        document.querySelectorAll('form').forEach(function (form) {
            if (form.hasAttribute('data-loader-ignore')) return;
            form.addEventListener('submit', function () {
                window.MobileAuthLoader.show();
            });
        });

        document.querySelectorAll('a[href]').forEach(function (link) {
            var href = link.getAttribute('href') || '';
            if (!href || href.charAt(0) === '#') return;
            if (href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) return;
            if (link.target && link.target !== '_self') return;

            link.addEventListener('click', function (event) {
                if (event.defaultPrevented) return;
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
                if (event.button && event.button !== 0) return;

                try {
                    var url = new URL(href, window.location.href);
                    if (url.origin !== window.location.origin) return;
                } catch (error) {
                    return;
                }

                window.MobileAuthLoader.show();
            });
        });

        window.addEventListener('pageshow', function () {
            window.MobileAuthLoader.hide();
        });
    });
</script>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/mobile/_auth-loader-script.blade.php ENDPATH**/ ?>