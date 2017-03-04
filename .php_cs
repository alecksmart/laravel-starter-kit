<?php

// for more info see:
// https://github.com/FriendsOfPHP/PHP-CS-Fixer#usage
// https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/master/UPGRADE.md

/*$fixers = [
    '-psr0',
    'align_double_arrow',
    'binary_operator_spaces',
    'blank_line_after_namespace',
    'blank_line_after_opening_tag',
    'blank_line_before_return',
    'braces',
    'cast_spaces',
    'class_definition',
    'concat_without_spaces',
    'elseif',
    'encoding',
    'full_opening_tag',
    'function_declaration',
    'function_typehint_space',
    'hash_to_slash_comment',
    'heredoc_to_nowdoc',
    'include',
    'lowercase_cast',
    'lowercase_constants',
    'lowercase_keywords',
    'method_argument_space',
    'method_separation',
    'native_function_casing',
    'new_with_braces',
    'no_alias_functions',
    'no_blank_lines_after_class_opening',
    'no_blank_lines_after_phpdoc',
    'no_blank_lines_between_uses',
    'no_closing_tag',
    'no_duplicate_semicolons',
    'no_empty_phpdoc',
    'no_extra_consecutive_blank_lines',
    'no_leading_import_slash',
    'no_leading_namespace_whitespace',
    'no_multiline_whitespace_around_double_arrow',
    'no_multiline_whitespace_before_semicolons',
    'no_short_bool_cast',
    'no_singleline_whitespace_before_semicolons',
    'no_spaces_after_function_name',
    'no_spaces_inside_parenthesis',
    'no_tab_indentation',
    'no_trailing_comma_in_list_call',
    'no_trailing_comma_in_singleline_array',
    'no_trailing_whitespace',
    'no_trailing_whitespace_in_comment',
    'no_unneeded_control_parentheses',
    'no_unreachable_default_argument_value',
    'no_unused_imports',
    'no_useless_return',
    'no_whitespace_before_comma_in_array',
    'no_whitespace_in_blank_lines',
    'not_operator_with_successor_space',
    'object_operator_without_whitespace',
    'ordered_imports',
    'phpdoc_align',
    'phpdoc_indent',
    'phpdoc_inline_tag',
    'phpdoc_no_access',
    'phpdoc_no_package',
    'phpdoc_order',
    'phpdoc_params',
    'phpdoc_scalar',
    'phpdoc_separation',
    'phpdoc_short_description',
    'phpdoc_summary',
    'phpdoc_to_comment',
    'phpdoc_trim',
    'phpdoc_type_to_var',
    'phpdoc_types',
    'phpdoc_var_without_name',
    'print_to_echo',
    'psr4',
    'self_accessor',
    'short_array_syntax',
    'short_scalar_cast',
    'simplified_null_return',
    'single_blank_line_at_eof',
    'single_blank_line_before_namespace',
    'single_import_per_statement',
    'single_line_after_imports',
    'single_quote',
    'space_after_semicolon',
    'standardize_not_equals',
    'switch_case_semicolon_to_colon',
    'switch_case_space',
    'ternary_operator_spaces',
    'trailing_comma_in_multiline_array',
    'trim_array_spaces',
    'unalign_equals',
    'unary_operator_spaces',
    'unix_line_endings',
    'visibility_required',
    'whitespace_after_comma_in_array',
];*/

if (class_exists('PhpCsFixer\Finder')) {    // PHP-CS-Fixer 2.x

    $finder = PhpCsFixer\Finder::create()
            ->notPath('bootstrap/cache')
            ->notPath('storage')
            ->notPath('vendor')
            ->in(__DIR__)
            ->name('*.php')
            ->notName('*.blade.php')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
    ;

    return PhpCsFixer\Config::create()
        ->setRules(array(
            '@PSR2' => true,
        ))
        ->setFinder($finder)
    ;
} elseif (class_exists('Symfony\CS\Finder\DefaultFinder')) {  // PHP-CS-Fixer 1.x
    $finder = Symfony\CS\Finder\DefaultFinder::create()
        ->in(__DIR__)
    ;

    return Symfony\CS\Config\Config::create()
        ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
        ->fixers(['-psr0'])    // don't lowercase namespace (use "namespace App\.." instead of "namespace app\..") to be compatible with Laravel 5
        ->finder($finder)
    ;
}