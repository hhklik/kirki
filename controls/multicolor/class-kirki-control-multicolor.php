<?php
/**
 * Customizer Control: multicolor.
 *
 * @package     Kirki
 * @subpackage  Controls
 * @copyright   Copyright (c) 2016, Aristeides Stathopoulos
 * @license     http://opensource.org/licenses/https://opensource.org/licenses/MIT
 * @since       2.2.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Multicolor control.
 */
class Kirki_Control_Multicolor extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'kirki-multicolor';

	/**
	 * Color Palette.
	 *
	 * @access public
	 * @var bool
	 */
	public $palette = true;

	/**
	 * Tooltips content.
	 *
	 * @access public
	 * @var string
	 */
	public $tooltip = '';

	/**
	 * Used to automatically generate all postMessage scripts.
	 *
	 * @access public
	 * @var array
	 */
	public $js_vars = array();

	/**
	 * Used to automatically generate all CSS output.
	 *
	 * @access public
	 * @var array
	 */
	public $output = array();

	/**
	 * Data type
	 *
	 * @access public
	 * @var string
	 */
	public $option_type = 'theme_mod';

	/**
	 * The kirki_config we're using for this control
	 *
	 * @access public
	 * @var string
	 */
	public $kirki_config = 'global';

	/**
	 * The translation strings.
	 *
	 * @access protected
	 * @since 2.3.5
	 * @var array
	 */
	protected $l10n = array();

	/**
	 * Enqueue control related scripts/styles.
	 *
	 * @access public
	 */
	public function enqueue() {
		wp_enqueue_script( 'kirki-multicolor', trailingslashit( Kirki::$url ) . 'controls/multicolor/multicolor.js', array( 'jquery', 'customize-base', 'wp-color-picker-alpha' ), false, true );
		wp_enqueue_style( 'kirki-multicolor-css', trailingslashit( Kirki::$url ) . 'controls/multicolor/multicolor.css', null );
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 *
	 * @see WP_Customize_Control::to_json()
	 */
	public function to_json() {
		parent::to_json();

		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		} else {
			$this->json['default'] = $this->setting->default;
		}
		$this->json['js_vars']     = $this->js_vars;
		$this->json['output']      = $this->output;
		$this->json['value']       = $this->value();
		$this->json['choices']     = $this->choices;
		$this->json['link']        = $this->get_link();
		$this->json['tooltip']     = $this->tooltip;
		$this->json['id']          = $this->id;
		$this->json['l10n']        = $this->l10n;
		$this->json['kirkiConfig'] = $this->kirki_config;

		if ( 'user_meta' === $this->option_type ) {
			$this->json['value'] = get_user_meta( get_current_user_id(), $this->id, true );
		}

		$this->json['inputAttrs'] = '';
		foreach ( $this->input_attrs as $attr => $value ) {
			$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
		}

		$this->json['palette']  = $this->palette;
	}

	/**
	 * An Underscore (JS) template for this control's content (but not its container).
	 *
	 * Class variables for this control class are available in the `data` JS object;
	 * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
	 *
	 * @see WP_Customize_Control::print_template()
	 *
	 * @access protected
	 */
	protected function content_template() {
		?>
		<span class="customize-control-title">
			{{{ data.label }}}
		</span>
		<# if ( data.description ) { #>
			<span class="description customize-control-description">{{{ data.description }}}</span>
		<# } #>
		<div class="multicolor-group-wrapper">
			<# for ( key in data.choices ) { #>
				<div class="multicolor-single-color-wrapper">
					<# if ( data.choices[ key ] ) { #>
						<label for="{{ data.id }}-{{ key }}">{{ data.choices[ key ] }}</label>
					<# } #>
					<input {{{ data.inputAttrs }}} id="{{ data.id }}-{{ key }}" type="text" data-palette="{{ data.palette }}" data-default-color="{{ data.default[ key ] }}" data-alpha="true" value="{{ data.value[ key ] }}" class="kirki-color-control color-picker multicolor-index-{{ key }}" />
				</div>
			<# } #>
		</div>
		<div class="iris-target"></div>
		<input type="hidden" value="" {{{ data.link }}} />
		<?php
	}
}
