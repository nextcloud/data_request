<?php

declare(strict_types=1);

namespace Nextcloud\CodingStandard;

use PhpCsFixer\Config as Base;
use PhpCsFixerCustomFixers;

class Config extends Base {
	public function __construct($name = 'default') {
		parent::__construct($name);
		$this->setIndent("\t");
		$this->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers());
	}

	public function getRules() : array {
		return [
			'@PSR1' => true,
			'@PSR2' => true,
			'align_multiline_comment' => true,
			'array_indentation' => true,
			'array_syntax' => true,
			'binary_operator_spaces' => [
				'default' => 'single_space',
			],
			'blank_line_after_namespace' => true,
			'blank_line_after_opening_tag' => true,
			'blank_lines_before_namespace' => ['min_line_breaks' => 2, 'max_line_breaks' => 2],
			'cast_spaces' => ['space' => 'none'],
			'concat_space' => ['spacing' => 'one'],
			'curly_braces_position' => [
				'classes_opening_brace' => 'same_line',
				'functions_opening_brace' => 'same_line',
			],
			'elseif' => true,
			'encoding' => true,
			'full_opening_tag' => true,
			'function_declaration' => [
				'closure_function_spacing' => 'one',
			],
			'indentation_type' => true,
			'line_ending' => true,
			'list_syntax' => true,
			'lowercase_cast' => true,
			'lowercase_keywords' => true,
			'method_argument_space' => [
				'on_multiline' => 'ignore',
			],
			'method_chaining_indentation' => true,
			'modifier_keywords' => [
				'elements' => ['property', 'method', 'const']
			],
			'no_closing_tag' => true,
			'no_extra_blank_lines' => [
				'tokens' => [
					'attribute', 'extra', 'parenthesis_brace_block', 'return', 'square_brace_block', 'switch', 'throw', 'use', 'use_trait'
				]
			],
			'no_leading_import_slash' => true,
			'no_short_bool_cast' => true,
			'no_spaces_after_function_name' => true,
			'no_spaces_inside_parenthesis' => true,
			'no_trailing_whitespace' => true,
			'no_trailing_whitespace_in_comment' => true,
			'no_unused_imports' => true,
			'no_whitespace_in_blank_line' => true,
			'nullable_type_declaration_for_default_null_value' => true,
			'nullable_type_declaration' => ['syntax' => 'question_mark'],
			'operator_linebreak' => [
				'position' => 'beginning',
			],
			'ordered_imports' => [
				'imports_order' => ['class', 'function', 'const'],
				'sort_algorithm' => 'alpha'
			],
			'phpdoc_align' => ['align' => 'left'],
			'phpdoc_single_line_var_spacing' => true,
			'phpdoc_var_annotation_correct_order' => true,
			'short_scalar_cast' => true,
			'single_blank_line_at_eof' => true,
			'single_class_element_per_statement' => true,
			'single_import_per_statement' => true,
			'single_line_after_imports' => true,
			'single_quote' => ['strings_containing_single_quote_chars' => false],
			'switch_case_space' => true,
			'trailing_comma_in_multiline' => ['elements' => ['parameters']],
			'types_spaces' => ['space' => 'none', 'space_multiple_catch' => 'none'],
			'type_declaration_spaces' => ['elements' => ['function', 'property']],
			'yoda_style' => ['equal' => false, 'identical' => false, 'less_and_greater' => false],
			PhpCsFixerCustomFixers\Fixer\MultilinePromotedPropertiesFixer::name() => true,
		];
	}
}
