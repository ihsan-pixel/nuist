# TODO: Add PDF View Button to Simfoni Index

## Step 1: Add pdfSimfoni() method to SimfoniAdminController
- [x] Add `pdfSimfoni($id)` method in `app/Http/Controllers/Admin/SimfoniAdminController.php`
- [x] Use Barryvdh\DomPDF\Facade\Pdf to generate PDF
- [x] Return PDF as inline (open in new tab) for user convenience

## Step 2: Add route in web.php
- [x] Add route `GET /admin/simfoni/pdf/{id}` → `SimfoniAdminController@pdfSimfoni`
- [x] Add named route `admin.simfoni.pdf`

## Step 3: Modify simfoni-template.blade.php
- [x] Remove HTML boilerplate (`<!DOCTYPE html>`, `<html>`, `<head>`, `<body>`)
- [x] Keep all styling and content intact
- [x] Adjust for DomPDF compatibility

## Step 4: Add View PDF button in index.blade.php
- [x] Add button with PDF icon (`bx-file-pdf`) in Action column
- [x] Link to `route('admin.simfoni.pdf', $simfoni->id)`
- [x] Use blue color and open in new tab (`target="_blank"`)

## Step 5: Test the implementation
- [ ] Verify button appears in Action column
- [ ] Verify PDF opens correctly in new tab
- [ ] Verify all data displays correctly in PDF

