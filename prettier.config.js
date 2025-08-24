/** @type {import('prettier').Config} */
module.exports = {
  // Explicit resolution prevents "cannot find package" errors
  plugins: [require.resolve('prettier-plugin-tailwindcss')],
  // General settings
  printWidth: 120,
  tabWidth: 4,
  useTabs: false,
  semi: true,
  singleQuote: true,
  // Note: PHP and Blade formatting disabled due to plugin installation issues
  // To enable PHP/Blade formatting:
  // 1. Ensure Node.js is properly installed and in PATH
  // 2. Install: npm install @shufo/prettier-plugin-blade --save-dev
  // 3. Add plugin to plugins array above
  // 4. Uncomment overrides below
  /*
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
  */
};
