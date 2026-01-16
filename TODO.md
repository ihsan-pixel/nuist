# TODO: Redesign Pengaturan UPPM Page

## Tasks
- [x] Redesign pengaturan.blade.php: Replace table with card grid layout for professional look
- [x] Improve form.blade.php: Better grouping, icons, and responsive design
- [x] Test the updated views for functionality and responsiveness
- [x] Ensure no errors in Blade syntax

## Information Gathered
- pengaturan.blade.php: Current layout uses a table with many columns, modals for add/edit/delete.
- form.blade.php: Form with multiple input fields for nominals, grouped in rows.
- Goal: Make it neat, professional, not tacky – use cards, better spacing, icons.

## Plan
- pengaturan.blade.php: Use Bootstrap grid with cards for each setting. Show key info (year, status, nominals summary), actions in card footer.
- form.blade.php: Group fields into sections with headings, add icons to labels, improve input styling.
- Dependent Files: pengaturan.blade.php, form.blade.php
- Followup: Run the app to check display.

## Next Steps
- Edit pengaturan.blade.php first
- Then edit form.blade.php
- Confirm with user
