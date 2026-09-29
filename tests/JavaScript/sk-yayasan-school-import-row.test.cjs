const { test } = require('node:test');
const assert = require('node:assert/strict');
const fs = require('node:fs');

const view = fs.readFileSync('resources/views/sk-yayasan/sekolah-index.blade.php', 'utf8');
const controller = fs.readFileSync('app/Http/Controllers/SkYayasanController.php', 'utf8');

test('admin import modal can append a new teacher row', () => {
    assert.match(view, /data-add-import-row/);
    assert.match(view, /Tambah Kolom Baru/);
    assert.match(view, /data-import-rows/);
    assert.match(view, /buildSchoolImportRowMarkup/);
    assert.match(view, /rows\[' \+ rowIndex \+ '\]\[row_number\]/);
});

test('school import update validates rows and creates missing submissions', () => {
    const method = controller.match(
        /public function updateSchoolImportBatchRows[\s\S]*?public function downloadImportBatchAttachment/
    )?.[0] ?? '';

    assert.match(method, /inspectEditableImportRows/);
    assert.match(method, /createMany\(\$this->buildImportBatchRowsPayload/);
    assert.match(method, /synchronizeBatchRequestsFromRows/);
});
