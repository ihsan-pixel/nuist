# NUIST UI Design System

This is the working visual reference for UI changes in the NUIST Laravel application. It documents the existing direction; it is not permission to replace the current theme.

## Foundation

- **CSS system:** Bootstrap 5.3.5 plus the existing Sass partials in `resources/scss/custom`.
- **Layouts:** reuse `resources/views/layouts/*` and the established vertical/horizontal navigation patterns.
- **Typography:** use the existing theme font stack and Bootstrap type scale before introducing new fonts.
- **Icons:** preserve the icon family already used by the affected screen. Existing families include Material Design Icons, Font Awesome, and limited Lucide usage.

## Visual language

- Light, professional, and compact enterprise UI.
- White or theme surface cards with low-contrast borders and restrained shadows.
- Green is the primary NUIST accent where the existing page already uses it; Bootstrap semantic colors remain the source of truth for status.
- Use radius and spacing tokens already present in the theme rather than one-off values.
- Keep dense data readable: short labels, strong headings, aligned numeric columns, and predictable action placement.

## Component guidance

- **Navigation:** preserve the active state, collapse behavior, and permission-aware menu structure.
- **Cards:** use cards for meaningful grouping; avoid nesting cards without a clear information hierarchy.
- **Tables:** keep headers visible, support horizontal scrolling on narrow screens, and do not hide critical columns without an alternative.
- **Forms:** pair every control with a visible label or accessible name; show validation and disabled states clearly.
- **Buttons:** use Bootstrap semantic variants consistently; primary actions should be visually distinct and have clear verbs.
- **Feedback:** provide empty, loading, success, warning, and error states for data-driven sections.

## Responsive checkpoints

Validate every affected screen at:

- 1440px desktop
- 1024px laptop/tablet landscape
- 768px tablet
- 390px mobile

At mobile widths, check navigation, tables, modals, charts, long labels, action groups, and horizontal overflow.

## Review checklist

- Existing layout and components were reused.
- No unrelated page or business behavior changed.
- Visual hierarchy is consistent with adjacent screens.
- Contrast and focus states remain usable.
- No content or controls overflow at mobile widths.
- Browser console is free of new errors.
- Screenshots were reviewed at the four responsive checkpoints.

