/** @type {import('prettier').Config} */
module.exports = {
  // Explicit resolution prevents “cannot find package” errors
  plugins: [require.resolve('prettier-plugin-tailwindcss')],
};
