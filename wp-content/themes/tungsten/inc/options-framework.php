<?php

/**
 * Represents an admin page. This class is not used directly. Instead, more specific child classes are used.
 **/
class Kanary_Options_Framework
{
	/**
	 * The option ID to be used in the main get_option() call.
	 *
	 * @access protected
	 * @var string
	 **/
	private $option_id;

	/**
	 * An array containing all the theme options.
	 *
	 * @access protected
	 * @var string
	 **/
	private $options;

	/**
	 * An array containing section information.
	 *
	 * @access protected
	 * @var string
	 **/
	private $sections;

	/**
	 * An array containing field information.
	 *
	 * @access protected
	 * @var string
	 **/
	private $fields;

	/**
	 * Class constructor. Initializes all the class variables.
	 *
	 * @var string $option_id The option ID that will be used in the get_option() function.
	 **/
	public function __construct($option_id)
	{
		$this->option_id = $option_id;
		$this->sections = $this->get_setting_sections();
		$this->fields = $this->process_fields($this->get_setting_fields());
		$this->options = $this->init_settings(get_option($option_id, array()));

		$this->register_hooks();
	}

	/**
	 * Function for returning an array of settings sections. This needs to be overriden by a child class in order for the options system to work.
	 *
	 * @return array Array of settings sections
	 **/
	protected function get_setting_sections()
	{
		wp_die(__CLASS__ . '::' . __FUNCTION__ . ' is an abstract function and needs to be overridden by a child class');
	}

	/**
	 * Adds missing arguments to each field in the passed array.
	 *
	 * @var array A 2D array containing the information for all the fields. In this array fields are grouped by sections.
	 * @return array A modified array in which all the fields have the requred arguments.
	 **/
	private function process_fields($fields)
	{
		foreach ($fields as $section_key => $section_fields) {
			$fields[ $section_key ] = $this->process_fields_partial($fields[ $section_key ]);
		}
		return $fields;
	}

	/**
	 * Adds missing arguments to each field in the passed array. This function is called recursively to cater nested fields.
	 *
	 * @var array A 2D array containing the information for all the fields. In this array the fields are not grouped.
	 * @return array A modified array in which all the fields have the requred arguments.
	 **/
	private function process_fields_partial($partial_fields)
	{
		foreach ($partial_fields as $field_key => $field) {
			if ($this->is_nested($field)) {
				$sub_fields = isset($field['items']) ? $field['items'] : array();
				$partial_fields[ $field_key ]['items'] = $this->process_fields_partial($sub_fields);
			} else {
				$partial_fields[ $field_key ] = $this->set_field_defaults($field);
			}
		}

		return $partial_fields;
	}

	/**
	 * Checks if a field is nested i.e. contains other fields in it.
	 *
	 * @var array $field Array containing Field information.
	 * @return bool Inidicating whether or not the field is nested.
	 **/
	private function is_nested($field)
	{
		return (isset($field['type']) && $field['type'] == 'nested');
	}

	/**
	 * Uses the wp_parse_args() function to add the missing arguments to a field array.
	 *
	 * @var array An array containing infomation about a single field.
	 * @return array The passed array with all the missing items filled in.
	 **/
	private function set_field_defaults($field)
	{
		$defaults = array(
			'label'                => '',
			'default'              => '',
			'type'                 => 'text',
			'choices'              => array(),
			'customizer'           => true,
			'disable_sanitization' => false
		);
		return wp_parse_args($field, $defaults);
	}

	/**
	 * Function for returning an array of settings fields. This needs to be overriden by a child class in order for the options system to work.
	 *
	 * @return array Array of settings sections
	 **/
	protected function get_setting_fields()
	{
		wp_die(__CLASS__ . '::' . __FUNCTION__ . ' is an abstract function and needs to be overridden by a child class');
	}

	/**
	 * Takes an array of options currently saved in the DB and fills in the blanks with default values.
	 *
	 * @var array An array of options.
	 * @return array An array with all the missing items filled with defaults.
	 **/
	public function init_settings($curr_options)
	{

		foreach ($this->fields as $section_key => $section_fields) {
			$curr_options[ $section_key ] = $this->init_partial(
				isset($curr_options[ $section_key ]) ? $curr_options[ $section_key ] : array(),
				$section_fields
			);
		}
		return $curr_options;
	}

	/**
	 * Responsible for filling in the blanks with default values. This function is called recursively to take care of nested fields.
	 *
	 * @var array A portion of the original options array.
	 * @var array An array of fields. This could consist of nested fields or the fields in a section.
	 * @return array The first argument with all the missing items filled with defaults.
	 **/
	public function init_partial($current_options, $partial_fields)
	{

		foreach ($partial_fields as $field_id => $field) {

			if ($this->is_nested($field)) {
				$sub_fields = isset($field['items']) ? $field['items'] : array();
				$current_options[ $field_id ] = $this->init_partial(
					isset($current_options[ $field_id ]) ? $current_options[ $field_id ] : array(),
					$sub_fields
				);
			} else {
				// TODO: Empty values are valid so they should be handled properly.
				if (!isset($current_options[ $field_id ])) {
					$current_options[ $field_id ] = $field['default'];
				}
			}
		}
		return $current_options;
	}

	/**
	 * Registers hooks with WordPress using add_action() and add_filter() functions.
	 **/
	protected function register_hooks()
	{
		add_action('customize_register', array($this, 'add_customizer_sections'));
	}

	public function get_options()
	{
		return $this->options;
	}

	public function add_customizer_sections($wp_customize)
	{
		foreach ($this->sections as $section_id => $section_title) {
			$customizer_section_id = $this->option_id . '_' . $section_id;

			$wp_customize->add_section(
				$customizer_section_id,
				array(
					'title' => $section_title
				)
			);
		}

		foreach ($this->fields as $section_id => $section_fields) {
			$this->add_customizer_fields(array($section_id), $section_fields, $wp_customize);
		}
	}

	function add_customizer_fields($indices, $partial_fields, $wp_customize)
	{
		if (
		is_array($partial_fields)
		) {
			foreach ($partial_fields as $field_id => $field) {
				$customizer_section_id = $this->option_id . '_' . $indices[0];
				$temp_indices = $indices;
				$temp_indices[] = $field_id;
				$field_name = $this->make_field_name($this->option_id, $temp_indices);

				if ($this->is_nested($field)) {
					$sub_fields = isset($field['items']) ? $field['items'] : array();
					if ($sub_fields) {
						$this->add_customizer_fields($temp_indices, $sub_fields, $wp_customize);
					}
				} else {
					if (!$field['customizer'])
						continue;

					// TODO: Convert $field_name into an ID.
					$wp_customize->add_setting(
						$field_name,
						array(
							'type'              => 'option',
							'default'           => $field['default'],
							'sanitize_callback' => $field['disable_sanitization'] ? array($this, 'no_sanitize') : array($this, 'sanitize')
						)
					);

					$wp_customize->add_control(
						$this->make_customizer_control($customizer_section_id, $field_name, $field, $wp_customize)
					);
				}
			}
		}
	}

	private function make_field_name($base, $indices)
	{
		$field_name = $base;
		foreach ($indices as $index) {
			$field_name .= '[' . $index . ']';
		}

		return $field_name;
	}

	private function make_customizer_control($customizer_section_id, $field_name, $field, $wp_customize)
	{
		$args = $field;

		$args['section'] = $customizer_section_id;
		$args['settings'] = $field_name;

		switch ($field['type']) {
			case 'media_url':
				unset($args['type']);
				$control = new WP_Customize_Image_Control($wp_customize, $field_name, $args);
				break;

			case 'media_items':
				unset($args['type']);
				$control = new WP_Customize_Media_Items_Control($wp_customize, $field_name, $args);
				break;

			case 'color':
				unset($args['type']);
				$control = new WP_Customize_Color_Control($wp_customize, $field_name, $args);
				break;

			default:
				$control = new WP_Customize_Control($wp_customize, $field_name, $args);
				break;
		}

		return $control;
	}

	public function sanitize($input)
	{
		if ($input === '')
			return $input;

		$input = wp_check_invalid_utf8($input);
		$input = wp_pre_kses_less_than($input);
		$input = wp_strip_all_tags($input);
		return $input;
	}

	public function no_sanitize($input)
	{
		return $input;
	}
}