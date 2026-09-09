const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

const source = fs.readFileSync('resources/views/mobile/izin-tugas-luar.blade.php', 'utf8');
const script = source.match(/<script>\s*([\s\S]*?)<\/script>/)[1];

function setup(withSwal = true) {
    const elements = new Map();
    const alerts = [];
    let request;
    function $(key) {
        if (typeof key === 'object') return key;
        if (!elements.has(key)) {
            elements.set(key, {
                handlers: {}, props: {}, attrs: {}, label: key === 'button' ? 'Kirim Izin Tugas Luar' : '',
                on(event, fn) { this.handlers[event] = fn; return this; },
                prop(name, value) { this.props[name] = value; return this; },
                attr(name, value) { this.attrs[name] = value; return this; },
                removeAttr(name) { delete this.attrs[name]; return this; },
                text(value) { if (value === undefined) return this.label; this.label = value; return this; },
                find() { return $('button'); }
            });
        }
        return elements.get(key);
    }
    $.ajax = options => { request = options; };
    const window = { location: {}, alert: message => alerts.push(message) };
    if (withSwal) window.Swal = { fire: options => { alerts.push(options.text); return Promise.resolve(); } };
    const context = vm.createContext({ $, window, FormData: class {} });
    vm.runInContext(script, context);
    const form = $('#form-izin-tugas-luar');
    const submit = () => form.handlers.submit.call(form, { preventDefault() {} });
    submit();
    return { $, form, alerts, context, submit, get request() { return request; } };
}

test('success displays feedback and prevents duplicate submission', () => {
    const h = setup();
    assert.equal(h.request.timeout, 60000);
    assert.equal(h.request.dataType, 'json');
    const firstRequest = h.request;
    h.submit();
    assert.equal(h.request, firstRequest);
    h.request.success({ success: true, message: 'Izin tersimpan' });
    h.request.complete();
    assert.deepEqual(h.alerts, ['Izin tersimpan']);
    assert.equal(h.$('button').props.disabled, true);
    assert.equal(h.form.attrs['aria-busy'], undefined);
});

for (const [name, xhr, status, expected] of [
    ['validation', { status: 422, responseJSON: { errors: { file_tugas: ['File maksimal 5 MB'] } } }, 'error', 'File maksimal 5 MB'],
    ['timeout', { status: 0 }, 'timeout', 'Periksa riwayat presensi'],
    ['expired session', { status: 419 }, 'error', 'Sesi Anda telah berakhir'],
    ['invalid JSON', { status: 200 }, 'parsererror', 'Respons server tidak dapat dibaca'],
    ['server error', { status: 500 }, 'error', 'Surat gagal terkirim']
]) {
    test(`${name} shows message and restores submission controls`, () => {
        const h = setup();
        h.request.error(xhr, status);
        h.request.complete();
        assert.ok(h.alerts[0].includes(expected));
        assert.equal(h.$('button').props.disabled, false);
        assert.equal(h.$('button').label, 'Kirim Izin Tugas Luar');
        assert.equal(h.context.isSubmitting, false);
        assert.equal(h.form.attrs['aria-busy'], undefined);
    });
}

test('feedback still works when SweetAlert CDN is unavailable', () => {
    const h = setup(false);
    h.request.success({ success: false, message: 'Masih ada izin pending' });
    h.request.complete();
    assert.deepEqual(h.alerts, ['Masih ada izin pending']);
    assert.equal(h.$('button').props.disabled, false);
});

const layout = fs.readFileSync('resources/views/layouts/mobile.blade.php', 'utf8');
const binding = layout.match(/\/\/ FORM SUBMIT\s*([\s\S]*?)\/\/ LINK NAVIGATION/)[1];
for (const [name, noLoader, prevented, expected] of [
    ['AJAX form', 'true', false, 0],
    ['prevented navigation', undefined, true, 0],
    ['normal form navigation', undefined, false, 1]
]) {
    test(`global loader handles ${name}`, () => {
        let callback;
        let loaderCalls = 0;
        const queued = [];
        vm.runInNewContext(binding, {
            document: { querySelectorAll: () => [{ dataset: { noLoader }, checkValidity: () => true, addEventListener: (_, fn) => { callback = fn; } }] },
            queueMicrotask: fn => queued.push(fn),
            showLoader: () => loaderCalls++
        });
        const event = { defaultPrevented: false };
        callback(event);
        event.defaultPrevented = prevented;
        queued.forEach(fn => fn());
        assert.equal(loaderCalls, expected);
    });
}
