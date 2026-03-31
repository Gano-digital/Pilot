<?php
namespace WprAddonsPro\Modules\TwitterFeedPro\Widgets;

use Elementor\Controls_Manager;
use WprAddons\Classes\Utilities;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

if ( ! defined( 'ABSPATH' ) ) exit;

class Wpr_Twitter_Feed_Pro extends \WprAddons\Modules\TwitterFeed\Widgets\Wpr_Twitter_Feed {
    public function add_control_number_of_posts() {
		$this->add_control(
			'number_of_posts',
			[
				'label' => esc_html__( 'Items Per Page', 'wpr-addons' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 6,
				'min' => 0
			]
		);
    }
}