const test = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const path = require('node:path');

const root = path.resolve(__dirname, '../..');

function environment(base, mode) {
    const writes = [];
    const links = Object.fromEntries(['bootstrap', 'app'].map(name => {
        let href = `${base}${name}.min.css?v=42`;
        return [`${name}-style`, {
            getAttribute: () => href,
            setAttribute: (_, value) => { href = value; writes.push(value); },
        }];
    }));
    return {
        writes,
        URL,
        window: {},
        sessionStorage: { getItem: () => mode, setItem() {} },
        document: {
            baseURI: 'https://nuist.test/portal/',
            getElementById: id => links[id],
            documentElement: { setAttribute() {}, removeAttribute() {} },
        },
    };
}

for (const folder of ['resources/js', 'public/build/js']) {
    const plugin = fs.readFileSync(path.join(root, folder, 'plugin.js'), 'utf8');
    const app = fs.readFileSync(path.join(root, folder, 'app.js'), 'utf8');
    const helper = app.slice(app.indexOf('    function setThemeStylesheet('), app.indexOf('    function updateThemeSetting('));

    test(`${folder}: reload light/dark does not rewrite stylesheets`, () => {
        for (const mode of ['light-mode-switch', 'dark-mode-switch']) {
            for (const base of ['build/css/', 'https://nuist.test/portal/build/css/', 'https://cdn.nuist.test/assets/css/']) {
                const context = environment(base, mode);
                context.window.sessionStorage = context.sessionStorage;
                vm.runInNewContext(plugin, context);
                assert.deepEqual(context.writes, []);
            }
        }
    });

    test(`${folder}: restored RTL changes each stylesheet only once`, () => {
        const context = environment('https://cdn.nuist.test/assets/css/', 'dark-rtl-mode-switch');
        context.window.sessionStorage = context.sessionStorage;
        vm.runInNewContext(plugin, context);
        vm.runInNewContext(plugin, context);
        assert.deepEqual(context.writes, [
            'https://cdn.nuist.test/assets/css/bootstrap-rtl.min.css?v=42',
            'https://cdn.nuist.test/assets/css/app-rtl.min.css?v=42',
        ]);
    });

    test(`${folder}: theme switching preserves asset URL and skips unchanged CSS`, () => {
        const context = environment('https://cdn.nuist.test/assets/css/', 'light-mode-switch');
        vm.runInNewContext(helper + `
            setThemeStylesheet('app-style', 'app.min.css');
            setThemeStylesheet('app-style', 'app-rtl.min.css');
            setThemeStylesheet('app-style', 'app-rtl.min.css');
            setThemeStylesheet('app-style', 'app.min.css');
            setThemeStylesheet('missing', 'app.min.css');
        `, context);
        assert.deepEqual(context.writes, [
            'https://cdn.nuist.test/assets/css/app-rtl.min.css?v=42',
            'https://cdn.nuist.test/assets/css/app.min.css?v=42',
        ]);
    });
}
