# TODO for Updating Tenaga Pendidik Details Modal

## Breakdown of Approved Plan

1. **Add necessary CSS and JS resources**:
   - Include Lucide Icons CDN in `@section('css')`.
   - Add Google Fonts for "Poppins" in `@section('css')`.
   - Add custom CSS styles for modal (fullscreen, blur, colors, animations, hover effects, gradients) in `@section('css')`.

2. **Update modal HTML structure**:
   - Replace the existing Bootstrap modal with a custom fullscreen modal using fixed positioning and overlay.
   - Implement header with green background (#16A085), title, and Lucide X close button.
   - Update body: Left column (photo with gradient border and shadow, name, email with icon, status badge with tooltip).
   - Right column: Two-column grid for all data fields with Lucide icons.
   - Add footer with "Edit Data" (green button with gradient hover) and "Tutup" (light gray button) buttons.
   - Ensure responsive design: Flex on desktop, stack on mobile.

3. **Update JavaScript**:
   - Keep existing data population logic.
   - Add copy-to-clipboard functionality for NIP and NUPTK using `navigator.clipboard`.
   - Add tooltip for status badge (using Bootstrap Tooltip or custom).
   - Implement modal animations (fade-in + slide-up) on show/hide.
   - Add hover effects for photo (zoom + glow) and buttons.

4. **Verify and complete**:
   - Ensure all data attributes from the table button are used.
   - Test responsiveness and features (to be done after edits via browser if needed).
   - Use `attempt_completion` once all changes are applied.

Progress: Step 1 completed - Added CSS and JS resources (Lucide, Google Fonts, custom styles).
Step 2 completed - Updated modal HTML structure with new design.
Step 3 completed - Updated JavaScript for modal functionality, copy-to-clipboard, tooltips, and animations.
Step 4 completed - Verified all changes; modal is now fullscreen with blur, responsive, and includes all required features.
