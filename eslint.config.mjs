import js from "@eslint/js";
import eslintConfigPrettier from "eslint-config-prettier";

export default [
    {
        ignores: [
            "var/**",
            "vendor/**",
            "public/bundles/**",
            "public/build/**",
            "node_modules/**",
            "assets/vendor/**"
        ]
    },

    js.configs.recommended,
    eslintConfigPrettier,
    {
        files: ["assets/**/*.js"],
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: "module",
            globals: {
                document: "readonly",
                window: "readonly",
                console: "readonly",
                setTimeout: "readonly",
                setInterval: "readonly",
                fetch: "readonly"
            }
        },
        rules: {
            "no-unused-vars": "warn",
            "no-console": "warn"
        }
    }
];