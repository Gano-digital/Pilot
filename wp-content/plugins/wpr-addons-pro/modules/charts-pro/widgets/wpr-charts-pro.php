<?php

namespace WprAddonsPro\Modules\ChartsPro\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Repeater;
use Elementor\Group_Control_Image_Size;
use WprAddons\Classes\Utilities;

// Security Note: Blocks direct access to the plugin PHP files.
defined('ABSPATH') || die();

class Wpr_Charts_Pro extends \WprAddons\Modules\Charts\Widgets\Wpr_Charts {

	public function add_control_choose_chart_data_source() {
		$this->add_control(
			'data_source',
			[
				'label'              => __( 'Data Source', 'wpr-addons' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'custom',
				'options'            => [
					'custom' => __( 'Custom', 'wpr-addons' ),
					'csv'    => 'CSV' . __( ' File', 'wpr-addons' ),
				],
				'frontend_available' => true,
			]
		);
	}

}