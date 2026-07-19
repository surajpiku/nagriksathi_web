module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/Livewire/**/*.php',
    './app/Filament/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          green:  '#1A6B3C',
          orange: '#F97316',
          light:  '#E8F5E9',
        },
        nagrik: {
          eligible: '#16A34A',
          pending:  '#F97316',
          urgent:   '#DC2626',
          info:     '#2563EB',
        }
      },
      fontFamily: {
        sans:  ['Poppins', 'sans-serif'],
        hindi: ['Noto Sans Devanagari', 'sans-serif'],
      },
    }
  },
  plugins: [require('daisyui')],
  daisyui: { themes: ['light'] }
}