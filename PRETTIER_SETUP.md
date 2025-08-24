# Prettier Setup for PHP and Blade Files

## Current Status
The project currently has Prettier configured for JavaScript, TypeScript, CSS, and HTML files, but **PHP and Blade file formatting is disabled** due to Node.js environment issues.

## To Enable PHP and Blade Formatting

### Prerequisites
1. Ensure Node.js is properly installed and available in your system PATH
2. Verify npm can install packages without errors

### Installation Steps

1. **Install the Blade Prettier plugin:**
   ```bash
   npm install @shufo/prettier-plugin-blade --save-dev
   ```

2. **Update `prettier.config.js`:**
   ```javascript
   /** @type {import('prettier').Config} */
   module.exports = {
     plugins: [
       require.resolve('@shufo/prettier-plugin-blade'),
       require.resolve('prettier-plugin-tailwindcss'), // Keep TailwindCSS last
     ],
     printWidth: 120,
     tabWidth: 4,
     useTabs: false,
     semi: true,
     singleQuote: true,
     overrides: [
       {
         files: '*.blade.php',
         options: {
           parser: 'blade',
           printWidth: 120,
           tabWidth: 4,
           wrapAttributes: 'auto',
           sortTailwindcssClasses: true,
         },
       },
     ],
   };
   ```

3. **Update `package.json` scripts to include Blade files:**
   ```json
   {
     "scripts": {
       "style:fix": "prettier --write \"resources/**/*.{js,ts,jsx,tsx,css,scss,html,blade.php,md}\" && stylelint \"resources/css/**/*.css\" --fix",
       "style:check": "prettier --check \"resources/**/*.{js,ts,jsx,tsx,css,scss,html,blade.php,md}\" && stylelint \"resources/css/**/*.css\" && if command -v rg >/dev/null 2>&1; then if rg 'style=' -n resources/views; then echo 'Inline styles found'; exit 1; fi; else echo 'ripgrep not found; skipping inline-style check'; fi"
     }
   }
   ```

### For PHP Files (Optional)
For pure PHP file formatting, you can also add:
```bash
npm install @prettier/plugin-php --save-dev --legacy-peer-deps
```

Note: PHP plugin may have compatibility issues with Prettier 3.x.

## Current Configuration
- ✅ JavaScript/TypeScript formatting: **Enabled**
- ✅ CSS formatting: **Enabled** 
- ✅ HTML formatting: **Enabled**
- ✅ Tailwind CSS class sorting: **Enabled**
- ❌ Blade template formatting: **Disabled** (requires plugin installation)
- ❌ PHP file formatting: **Disabled** (requires plugin installation)

## Testing
Once plugins are installed, test with:
```bash
npm run style:check
npm run style:fix
```

## Troubleshooting
- If you get "cannot find package" errors, ensure the plugins are installed in `node_modules`
- If Node.js isn't found, add it to your system PATH
- Use `--legacy-peer-deps` flag if there are dependency conflicts