# Style Guide

This project uses a modular CSS structure powered by Tailwind CSS.

## Tokens
- Defined in `resources/css/tokens.css` as CSS variables with light/dark support.
- Core variables include color palette (`--primary`, `--secondary`, etc.), radii, and typography.

## Components
Reusable component classes live in `resources/css/components.css`.

 - `btn`, `btn-secondary`, `btn-destructive` – button variants
 - `card` – card container
 - `form-input` – base input styling
 - `form-label`, `heading-xl`, `body-sm-muted`, `error-text`, `link-primary`

## Utilities
Custom utilities exist in `resources/css/utilities.css` for text and border colors.

## Themes & Animations
- `resources/css/themes.css` contains theme helpers.
- `resources/css/animations.css` centralizes keyframes such as `modal-appear`.

## Usage
- Prefer component classes over long utility chains for repeated patterns.
- Use dark mode variables via CSS variables; Tailwind `dark:` variants are supported.

## Adding Components
1. Add styles to `resources/css/components.css` under `@layer components`.
2. Document the component and variants in this guide.
3. Run `npm run style:fix` before committing.
