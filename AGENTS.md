# NUIST UI/UX Engineering Rules

## Project context

- Laravel Blade application using Vite, Sass, and Bootstrap 5.3.5.
- Existing visual foundation is the Skote-based layout and the styles under `resources/scss`.
- Preserve the current application structure, permissions, routes, and business logic.

## Design direction

- Professional education-management product: clear, compact, calm, and information-dense.
- Use the existing light/dark theme system and established NUIST brand colors.
- Prefer subtle surfaces, restrained shadows, and clear hierarchy over decorative glass effects.
- Do not introduce Tailwind, shadcn/ui, another CSS framework, or a second icon system without explicit approval.

## Implementation rules

- Inspect the nearest existing layout and component before creating a new pattern.
- Reuse Bootstrap utilities and existing Sass partials where they fit.
- Keep page-specific styles scoped and move reusable styles into the appropriate `resources/scss/custom` partial.
- Use existing icon families consistently within the page; do not replace icons globally as part of an unrelated UI task.
- Preserve accessible labels, keyboard behavior, focus states, contrast, and responsive overflow handling.
- Do not invent data, remove functionality, alter authorization, or change routes/database behavior for a visual task.

## Required UI workflow

1. Audit the affected Blade layout, Sass partials, assets, and JavaScript.
2. State the intended visual change and affected screens before a broad redesign.
3. Implement the smallest coherent change within the existing Bootstrap/Sass system.
4. Build assets with `npm run build` when frontend files change.
5. Validate the affected page in a browser at 1440px, 1024px, 768px, and 390px widths.
6. Check alignment, overflow, contrast, focus states, empty/loading/error states, and console errors.
7. Compare screenshots before and after; do not declare visual work complete without browser validation.

## Scope guardrails

- Never redesign unrelated pages.
- Never modify business logic, migrations, routes, authorization, or API contracts for a UI-only request.
- Never overwrite existing user changes; inspect `git status` first and keep unrelated diffs intact.
- Ask for approval before adding dependencies, changing the frontend framework, or making a cross-application visual redesign.

