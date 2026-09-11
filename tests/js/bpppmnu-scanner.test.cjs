const test = require('node:test');
const assert = require('node:assert/strict');
const vm = require('node:vm');
const fs = require('node:fs');
const path = require('node:path');
const {execFileSync} = require('node:child_process');
const rootPath = path.resolve(__dirname, '../..');
const source = fs.readFileSync(path.join(rootPath, 'public/js/bpppmnu-scanner.js'), 'utf8');
const token = 'a'.repeat(64);

function scanner({fetchFails = false, cameraDenied = false, responseOk = true} = {}) {
    const elements = {};
    const make = id => elements[id] = {hidden: false, disabled: false, listeners: {}, addEventListener(type, fn) { this.listeners[type] = fn; }};
    for (const id of ['scanner', 'qr-video', 'qr-status', 'qr-start', 'qr-stop']) make(id);
    elements.scanner.dataset = {eventId: '4', scanUrl: '/mobile/bpppmnu/kegiatan/4/scan'};
    Object.assign(elements['qr-video'], {readyState: 2, videoWidth: 640, videoHeight: 480, play: async () => {}});
    let stopped = 0, request;
    const winEvents = {};
    const document = {
        getElementById: id => elements[id],
        createElement: () => ({getContext: () => ({drawImage() {}, getImageData: () => ({data: [], width: 2, height: 2})})}),
        querySelector: () => ({content: 'csrf-test'}),
        addEventListener() {},
    };
    vm.runInNewContext(source, {
        document,
        window: {isSecureContext: true, jsQR: () => ({data: JSON.stringify({type: 'bpppmnu', event_id: 4, token})}), addEventListener: (name, cb) => winEvents[name] = cb},
        navigator: {mediaDevices: {getUserMedia: async () => {
            if (cameraDenied) { const error = new Error('denied'); error.name = 'NotAllowedError'; throw error; }
            return {getTracks: () => [{stop: () => stopped++}]};
        }}},
        fetch: async (url, options) => {
            request = {url, options};
            if (fetchFails) throw new TypeError('network');
            return {ok: responseOk, json: async () => responseOk ? {message: 'Presensi berhasil dicatat.', event_name: 'Rapat', attended_at: '09:00 WIB'} : {message: 'Waktu presensi kegiatan telah berakhir.'}};
        },
        setTimeout: () => 1, clearTimeout() {}, TypeError,
    });
    return {elements, start: async () => {await elements['qr-start'].listeners.click(); await new Promise(resolve => setImmediate(resolve));}, stopped: () => stopped, request: () => request, winEvents};
}

test('valid scan sends token with CSRF, stops camera, and waits for server confirmation', async () => {
    const s = scanner(); await s.start();
    assert.equal(s.request().options.headers['X-CSRF-TOKEN'], 'csrf-test');
    assert.equal(JSON.parse(s.request().options.body).qr_token, token);
    assert.equal(s.stopped(), 1);
    assert.equal(s.elements['qr-start'].textContent, 'Sudah Hadir');
    assert.equal(s.elements['qr-start'].disabled, true);
});

test('network failure never reports attendance success and allows retry', async () => {
    const s = scanner({fetchFails: true}); await s.start();
    assert.match(s.elements['qr-status'].textContent, /belum terkonfirmasi/);
    assert.equal(s.elements['qr-start'].disabled, false);
    assert.equal(s.stopped(), 1);
});

test('server rejection is shown without marking the user present', async () => {
    const s = scanner({responseOk: false}); await s.start();
    assert.match(s.elements['qr-status'].textContent, /telah berakhir/);
    assert.equal(s.elements['qr-start'].disabled, false);
});

test('camera denial shows an actionable message and does not send attendance', async () => {
    const s = scanner({cameraDenied: true}); await s.start();
    assert.match(s.elements['qr-status'].textContent, /Izin kamera ditolak/);
    assert.equal(s.request(), undefined);
});

test('BaconQrCode generated payload can be decoded by the shipped jsQR', () => {
    const php = String.raw`require 'vendor/autoload.php'; $payload=json_encode(['type'=>'bpppmnu','event_id'=>4,'token'=>str_repeat('a',64)]); $qr=\BaconQrCode\Encoder\Encoder::encode($payload, \BaconQrCode\Common\ErrorCorrectionLevel::L()); $m=$qr->getMatrix(); $rows=[]; for($y=0;$y<$m->getHeight();$y++){ $row=[]; for($x=0;$x<$m->getWidth();$x++)$row[]=$m->get($x,$y); $rows[]=$row; } echo json_encode($rows);`;
    const matrix = JSON.parse(execFileSync('php', ['-r', php], {cwd: rootPath, encoding: 'utf8'}));
    const scale = 6, border = 4, width = (matrix.length + border * 2) * scale;
    const pixels = new Uint8ClampedArray(width * width * 4).fill(255);
    matrix.forEach((row,y) => row.forEach((black,x) => {
        if (!black) return;
        for(let dy=0;dy<scale;dy++) for(let dx=0;dx<scale;dx++) {
            const i=(((y+border)*scale+dy)*width+(x+border)*scale+dx)*4;
            pixels[i]=pixels[i+1]=pixels[i+2]=0;
        }
    }));
    const decode = require(path.join(rootPath, 'public/vendor/jsqr/jsQR.js'));
    const qr = decode(pixels, width, width);
    assert.ok(qr);
    assert.deepEqual(JSON.parse(qr.data), {type:'bpppmnu',event_id:4,token});
});
