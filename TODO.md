# TODO: Update Login Page to Use New Template

## Tasks
- [x] Update `resources/views/auth/login.blade.php`:
  - [x] Add the new CSS (inline in @section('css') for simplicity, overriding Bootstrap where needed).
  - [x] Replace @section('content') with the new HTML structure, adapting the form to match (keep Laravel form action, CSRF, and validation).
  - [x] Change input labels to "Username" (but keep name="email" for functionality), password, and login button.
  - [x] Add social login buttons as placeholders (non-functional).
  - [x] Include the right-side image and footer with provided content.
- [x] No new dependencies; reuse existing assets.

## Followup Steps
- [ ] Test the login page for layout and responsiveness.
- [ ] Verify form submission and validation still work.
- [ ] If needed, create a separate CSS file (e.g., public/css/login-style.css) and link it.
