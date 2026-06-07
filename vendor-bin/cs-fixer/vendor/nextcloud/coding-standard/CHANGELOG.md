# Changelog
All notable changes to this project will be documented in this file.

## 1.5.0 — 2026-05-19
### Added
* `blank_lines_before_namespace`: Make sure a blank line precedes the namespace declaration
* `no_extra_blank_lines`: Remove extra blank lines where they are not needed to improve readability

## 1.4.0 — 2025-07-19
### Added
* `no_whitespace_in_blank_line`: Remove trailing whitespace at the end of blank lines
* `operator_linebreak`: Split conditional statements into multiple lines and place operator at the beginning

## 1.3.2 - 2024-10-14
### Added
* `type_declaration_spaces`: A single space between typehint and variable name

## 1.3.1 - 2024-09-19
### Fixed
* Removed misbehaving `ErickSkrauch/blank_line_before_return` and `ErickSkrauch/line_break_after_statements` rules

## 1.3.0 - 2024-09-18
### Changed
* `trailing_comma_in_multiline`: Add a trailing comma to multline function parameters
* `MultilinePromotedPropertiesFixer`: Break promoted properties on multiple lines
* `ErickSkrauch/blank_line_before_return`: Add a blank line before each return
* `ErickSkrauch/line_break_after_statements`: Add a blank line after all control statements
* `concat_space`: Concatenation should be spaced
* `nullable_type_declaration`: Changes `DateTimeInterface|null` to `?DateTimeInterface`

## 1.2.3 - 2024-08-23
### Changed
* `cast_spaces`: No space between cast and variable

## 1.2.2 - 2024-08-23
### Added
* `cast_spaces`: A single space between cast and variable
* `lowercase_cast`: Cast should be written in lower case
* `method_chaining_indentation`: Use the same indentation when changing methods
* `no_short_bool_cast`: Short cast bool using double exclamation mark should not be used
* `phpdoc_align`: All items of the given PHPDoc tags must be left-aligned
* `phpdoc_single_line_var_spacing`: Single line @var PHPDoc should have proper spacing
* `phpdoc_var_annotation_correct_order`: Enforce the correct order for phpdoc annotations
* `short_scalar_cast`: (boolean) => (bool), (integer) => (int), ...
* `single_quote`: Use single quotes for simple strings
* `types_spaces`: No spaces around union and intersection type operators

## 1.2.1 - 2024-02-01
### Fix
* fix: Remove `fully_qualified_strict_types` again by @nickvergessen in https://github.com/nextcloud/coding-standard/pull/16

## 1.2.0 - 2024-02-01
### Added
- `array_syntax`: Force short syntax for array
- `list_syntax`: Same for list
- ~~`fully_qualified_strict_types`: Remove namespace from classname when there is a `use` statement, and add missing backslash for global namespace~~ - Removed in 1.2.1 due to issues
- `no_leading_import_slash`: Remove leading slash from `use` statement
- `nullable_type_declaration_for_default_null_value`: Add missing `?` on type declaration for parameters defaulting to `null`. This will most likely be needed to avoid warnings in PHP 8.4.
- `yoda_style`: forbid yoda style comparision. This replaces `null === $a` by `$a === null`.

## 1.1.1 - 2023-06-23
### Changed
* feat: use php-cs-fixer/shim by @kesselb in https://github.com/nextcloud/coding-standard/pull/13

## 1.1.0 - 2023-04-13
### Changed
* Order imports alphabetically by @come-nc in https://github.com/nextcloud/coding-standard/pull/10
* fix(rules): Replace deprecated braces rules by @nickvergessen in https://github.com/nextcloud/coding-standard/pull/12

## 1.0.0 – 2021-11-10
### Breaking change
* Update php-cs-fixer to 3.x
* See https://github.com/nextcloud/coding-standard#upgrade-from-v0x-to-v10 for instructions.

## 0.5.0 – 2021-01-11
### Added
- New rule: short list syntax
- php7.2 support (back, for apps that support Nextclod 20 - 21)

## 0.4.0 – 2020-12-14
### Added
- php8 support
- New rule: binary operators should be surrounded by a single space
### Changed
- php-cs-fixer updated to the latest version
