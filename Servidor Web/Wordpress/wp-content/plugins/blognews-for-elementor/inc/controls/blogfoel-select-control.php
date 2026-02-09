<?php
use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) exit;

class BLOGFOELSELECT extends Base_Data_Control {
	
	public function get_type() {
		return 'blogfoel-select';
	}

	protected function get_default_settings() {
		return [
			'label_block' => true,
		];
	}

	public function content_template() {
		?>
		<div class="elementor-control-field"> 
			<# if ( data.label ) { #>
				<label class="elementor-control-title">{{{ data.label }}}</label>
			<# } #>
			<div class="elementor-control-input-wrapper elementor-control-unit-5">
				<select class="custom-select-control" data-setting="{{ data.name }}">
					<# _.each( data.options, function( label, value ) { #>
					<option value="{{ value }}"
						<# if ( value.indexOf('blogfoel-pro-') === 0 ) { #>
							disabled
						<# } #>
						<# if ( value == data.controlValue ) { #>
							selected="selected"
						<# } #>
					>{{{ label }}}</option>
				<# }); #>
				</select>
			</div>
		</div>
		<?php
	}
}