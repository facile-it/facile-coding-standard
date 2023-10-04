Changelog
---------

## [1.0.0] - TBA
- Bumping minimum PHP version required to 7.4
- Bumping minimum PHP-CS-Fixer version required to 3.4
- Dynamically replace deprecated rules depending on the PHP-CS-Fixer version in use

### New rules
The following rules or groups have been added to the default rule set:
- `@PER-CS2.0` (replacing `@PSR2`)
- `@PER-CS2.0:risky` (in `RiskyRulesProvider`)
- `@DoctrineAnnotation`
- `blank_lines_before_namespace`
- `class_reference_name_casing`
- `curly_braces_position`
- `declare_parentheses`
- `empty_loop_body`
- `integer_literal_case`
- `linebreak_after_opening_tag`
- `list_syntax`
- `long_to_shorthand_operator`
- `lowercase_static_reference`
- `magic_constant_casing`
- `magic_method_casing`
- `native_function_type_declaration_casing`
- `native_type_declaration_casing`
- `no_alternative_syntax`
- `no_blank_lines_after_class_opening`
- `no_trailing_comma_in_singleline`
- `no_trailing_comma_in_singleline_function_call`
- `no_unneeded_curly_braces`
- `no_unneeded_import_alias`
- `no_unneeded_braces`
- `no_unset_cast`
- `nullable_type_declaration`
- `octal_notation`
- `phpdoc_tag_casing`
- `return_to_yield_from`
- `phpdoc_trim_consecutive_blank_line_separation`
- `semicolon_after_instruction`
- `single_line_comment_spacing`
- `single_line_empty_body`
- `single_space_around_construct`
- `single_trait_insert_per_statement`
- `type_declaration_spaces`
- `types_spaces`
- `no_homoglyph_names`
- `set_type_to_cast`

## [0.5.3] - 2023-09-13
- Disable "phpdoc_to_comment" option to avoid false positives with PHPStan @var helpers #46

## [0.5.2] - 2022-05-02
- Allow Symfony 6

## [0.5.1] - 2021-09-02
- Fix configuration generation
- Check for --no-interactive flag during BC upgrades

## [0.5.0] - 2021-09-01
- Upgrade to PHP-CS-Fixer 3.0 #35 (all breaking changes are due to upstream, check the [UPGRADE-v3.md](https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/v3.0.2/UPGRADE-v3.md) document for a complete list)

## [0.4.1] - 2021-02-17
### Added
- Allow PHP 8

## [0.4.0] - 2020-10-31
## Changed
- Bumped minimum required PHP version to 7.2
- Composer 2.0 compatibility

## [0.3.1] - 2018-09-19
## Changed
- Create configuration without risky rules by default
- Removed risky rules from default configuration

## [0.3.0] - 2018-09-15
## Changed
- Bumped minimum required PHP version to 7.1
- Bumped minimum php-cs-fixer to v2.13
- Added new rules
- Changed configuration
- Plugin refactoring


## [0.2.0] - 2018-02-01
## Removed
- Removed `self_accessor` (it became risky)

## [0.1.0] - 2017-08-30
### Added
- Added first configuration

