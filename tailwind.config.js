import path from "path";
import forms from "@tailwindcss/forms";
import typography from "@tailwindcss/typography";

const preset = require("../../../../vendor/filament/filament/tailwind.config.preset");

export default {
  // presets: [preset],
  content: [
    "./resources/views/**/*.blade.php",
    "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    "./vendor/laravel/jetstream/**/*.blade.php",
    "./storage/framework/views/*.php",
    "./storage/app/plugins/**/resources/**/*.blade.php",
    "./storage/app/plugins/**/resources/**/*.js",
  ],
  plugins: [forms, typography],
};
