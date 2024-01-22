Changelog
---------

## [1.2.0] - 2024-01-22
- Add new `numeric_literal_separator` rule (#65)
- Map new heredoc rules as "to be discussed" (`heredoc_closing_marker`, `multiline_string_to_heredoc`) 

## [1.1.0] - 2023-12-28
- Add new risky `class_keyword` rule (#64)
  NB: the rule is experimental, and relies on runtime autoload to determine if a FCQN refers to an existing class
- Test against PHP 8.3

## [1.0.1] - 2023-11-30
- Allow Symfony 7
- Move `long_to_shorthand_operator` to `RiskyRulesProvider` (#62)

## [1.0.0] - 2023-10-30
- Bumping minimum PHP version required to 7.4
- Bumping minimum PHP-CS-Fixer version required to 3.4
- Dynamically replace deprecated rules depending on the PHP-CS-Fixer version in use

### New rules
The following rules or groups have been added to the default rule set:
- `@PER-CS2.0` (with `@PSR12` as fallback)
- `@PER-CS2.0:risky` (with `@PSR12:risky` as fallback)
- `@DoctrineAnnotation`
- `array_push`
- `assign_null_coalescing_to_coalesce_equal`
- `attribute_empty_parentheses`
- `backtick_to_shell_exec`
- `class_reference_name_casing`
- `combine_consecutive_issets`
- `combine_consecutive_unsets`
- `combine_nested_dirname`
- `curly_braces_position`
- `declare_parentheses`
- `empty_loop_body`
- `ereg_to_preg`
- `explicit_string_variable`
- `get_class_to_class_keyword`
- `heredoc_to_nowdoc`
- `heredoc_indentation`
- `implode_call`
- `integer_literal_case`
- `lambda_not_used_import`
- `linebreak_after_opening_tag`
- `list_syntax`
- `long_to_shorthand_operator`
- `magic_constant_casing`
- `magic_method_casing`
- `modernize_strpos`
- `native_function_type_declaration_casing`
- `native_type_declaration_casing`
- `no_alternative_syntax`
- `no_superfluous_elseif`
- `no_superfluous_phpdoc_tags`
- `no_trailing_comma_in_singleline`
- `no_trailing_comma_in_singleline_function_call`
- `no_unneeded_import_alias`
- `no_unneeded_braces`
- `no_unset_cast`
- `no_useless_concat_operator`
- `no_useless_else`
- `no_useless_sprintf`
- `nullable_type_declaration`
- `octal_notation`
- `operator_linebreak`
- `ordered_traits`
- `phpdoc_inline_tag_normalizer`
- `phpdoc_no_alias_tag`
- `phpdoc_param_order`
- `phpdoc_summary`
- `phpdoc_tag_casing`
- `phpdoc_tag_type`
- `phpdoc_var_annotation_correct_order`
- `php_unit_data_provider_static`
- `php_unit_dedicate_assert_internal_type`
- `php_unit_expectation`
- `php_unit_fqcn_annotation`
- `php_unit_mock_short_will_return`
- `return_to_yield_from`
- `phpdoc_trim_consecutive_blank_line_separation`
- `semicolon_after_instruction`
- `single_line_comment_spacing`
- `single_line_empty_body`
- `single_space_around_construct`
- `single_trait_insert_per_statement`
- `switch_continue_to_break`
- `type_declaration_spaces`
- `types_spaces`
- `no_homoglyph_names`
- `set_type_to_cast`
- `ternary_to_elvis_operator`


### Changes to existing rules
- `phpdoc_align` is now enabled with config `left`
- `ordered_imports` is now falling back to PER-CS configuration, which is not the same as the default one

### Removed rules
Multiple rules are removed (but still applied) since they're already covered in PER-CS or PSR-12 with the same configuration as before:
- `blank_line_after_namespace`
- `blank_line_after_opening_tag`
- `compact_nullable_type_declaration`
- `declare_equal_normalize`
- `lowercase_cast`
- `new_with_parentheses`
- `no_leading_import_slash`
- `no_whitespace_in_blank_line`
- `return_type_declaration`
- `short_scalar_cast`
- `ternary_operator_spaces`
- `unary_operator_spaces`

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

