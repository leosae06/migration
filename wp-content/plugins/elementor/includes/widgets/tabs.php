<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Widget_Tabs extends Widget_Base {

  public function get_elementor_templates($type = null)
    {

        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];

        if ($type) {

            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }

        $page_templates = get_posts($args);

        $options[0] = esc_html__('Select a Template', 'vlthemes');

        if (!empty($page_templates) && !is_wp_error($page_templates)) {
            foreach ($page_templates as $post) {
                $options[$post->ID] = $post->post_title;
            }
        } else {

            $options[0] = esc_html__('Create a Template First', 'vlthemes');
        }

        return $options;
    }

	/**
	 * Get widget name.
	 *
	 * Retrieve tabs widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tabs';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve tabs widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Tabs', 'elementor' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve tabs widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'tabs', 'accordion', 'toggle' ];
	}

	public function show_in_panel(): bool {
		return ! Plugin::$instance->experiments->is_feature_active( 'nested-elements' );
	}

  /**
     * Register the widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function _register_controls()
    {

        $start = is_rtl() ? 'end' : 'start';
        $end = is_rtl() ? 'start' : 'end';

        $this->start_controls_section(
            'section_tabs',
            [
                'label' => esc_html__('Tabs', 'elementor'),
            ]
        );



        $repeater = new Repeater();
        $repeater->add_control(
            'template',
            [
                'label' => esc_html__('Choose Template', 'vlthemes'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_elementor_templates(),
                'label_block' => true,
            ]
        );
        $repeater->add_control(
            'tab_title',
            [
                'label' => esc_html__('Title', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__('Tab Title', 'elementor'),
                'placeholder' => esc_html__('Tab Title', 'elementor'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );




        $this->add_control(
            'tabs',
            [
                'label' => esc_html__('Tabs Items', 'elementor'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ tab_title }}}',
            ]
        );


        $this->add_control(
            'type',
            [
                'label' => esc_html__('Position', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'horizontal',
                'options' => [
                    'horizontal' => esc_html__('Horizontal', 'elementor'),
                    'vertical' => esc_html__('Vertical', 'elementor'),
                ],
                'prefix_class' => 'elementor-tabs-view-',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'tabs_align_horizontal',
            [
                'label' => esc_html__('Alignment', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '' => [
                        'title' => esc_html__('Start', 'elementor'),
                        'icon' => "eicon-align-$start-h",
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor'),
                        'icon' => 'eicon-align-center-h',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'elementor'),
                        'icon' => "eicon-align-$end-h",
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'elementor'),
                        'icon' => 'eicon-align-stretch-h',
                    ],
                ],
                'prefix_class' => 'elementor-tabs-alignment-',
                'condition' => [
                    'type' => 'horizontal',
                ],
            ]
        );

        $this->add_control(
            'tabs_align_vertical',
            [
                'label' => esc_html__('Alignment', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    '' => [
                        'title' => esc_html__('Start', 'elementor'),
                        'icon' => 'eicon-align-start-v',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'elementor'),
                        'icon' => 'eicon-align-center-v',
                    ],
                    'end' => [
                        'title' => esc_html__('End', 'elementor'),
                        'icon' => 'eicon-align-end-v',
                    ],
                    'stretch' => [
                        'title' => esc_html__('Stretch', 'elementor'),
                        'icon' => 'eicon-align-stretch-v',
                    ],
                ],
                'prefix_class' => 'elementor-tabs-alignment-',
                'condition' => [
                    'type' => 'vertical',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render the widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.1.0
     *
     * @access protected
     */
    protected function render()
    {
        $tabs = $this->get_settings_for_display('tabs');

        $id_int = substr($this->get_id_int(), 0, 3);

        $this->add_render_attribute('elementor-tabs', 'class', 'elementor-tabs');

?>
        <div <?php $this->print_render_attribute_string('elementor-tabs'); ?>>
            <div class="elementor-tabs-wrapper" role="tablist">
                <?php
                foreach ($tabs as $index => $item) :
                    $tab_count = $index + 1;
                    $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);

                    $this->add_render_attribute($tab_title_setting_key, [
                        'id' => 'elementor-tab-title-' . $id_int . $tab_count,
                        'class' => ['elementor-tab-title', 'elementor-tab-desktop-title'],
                        'aria-selected' => 1 === $tab_count ? 'true' : 'false',
                        'data-tab' => $tab_count,
                        'role' => 'tab',
                        'tabindex' => 1 === $tab_count ? '0' : '-1',
                        'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
                        'aria-expanded' => 'false',
                    ]);
                ?>
                    <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>><?php
                                                                                                // PHPCS - the main text of a widget should not be escaped.
                                                                                                echo $item['tab_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                                                                ?></div>
                <?php endforeach; ?>
            </div>
            <div class="elementor-tabs-content-wrapper" role="tablist" aria-orientation="vertical">
                <?php
                foreach ($tabs as $index => $item) :
                    $tab_count = $index + 1;
                    $hidden = 1 === $tab_count ? 'false' : 'hidden';
                    $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);

                    $tab_title_mobile_setting_key = $this->get_repeater_setting_key('tab_title_mobile', 'tabs', $tab_count);

                    $this->add_render_attribute($tab_content_setting_key, [
                        'id' => 'elementor-tab-content-' . $id_int . $tab_count,
                        'class' => ['elementor-tab-content', 'elementor-clearfix'],
                        'data-tab' => $tab_count,
                        'role' => 'tabpanel',
                        'aria-labelledby' => 'elementor-tab-title-' . $id_int . $tab_count,
                        'tabindex' => '0',
                        'hidden' => $hidden,
                    ]);
                    $template_id = $item['template'];

                    if ('publish' !== get_post_status($template_id)) {
                        return;
                    }


                    $this->add_render_attribute($tab_title_mobile_setting_key, [
                        'class' => ['elementor-tab-title', 'elementor-tab-mobile-title'],
                        'aria-selected' => 1 === $tab_count ? 'true' : 'false',
                        'data-tab' => $tab_count,
                        'role' => 'tab',
                        'tabindex' => 1 === $tab_count ? '0' : '-1',
                        'aria-controls' => 'elementor-tab-content-' . $id_int . $tab_count,
                        'aria-expanded' => 'false',
                    ]);

                    $this->add_inline_editing_attributes($tab_content_setting_key, 'advanced');
                ?>
                    <div <?php $this->print_render_attribute_string($tab_title_mobile_setting_key); ?>><?php
                                                                                                        $this->print_unescaped_setting('tab_title', 'tabs', $index);
                                                                                                        ?></div>
                    <div <?php $this->print_render_attribute_string($tab_content_setting_key); ?>><?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($template_id, true);

                                                                                                    ?></div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php
    }


	/**
	 * Render tabs widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<div class="elementor-tabs" role="tablist" aria-orientation="vertical">
			<# if ( settings.tabs ) {
				var elementUid = view.getIDInt().toString().substr( 0, 3 ); #>
				<div class="elementor-tabs-wrapper" role="tablist">
					<# _.each( settings.tabs, function( item, index ) {
						var tabCount = index + 1,
							tabUid = elementUid + tabCount,
							tabTitleKey = 'tab-title-' + tabUid;

					view.addRenderAttribute( tabTitleKey, {
						'id': 'elementor-tab-title-' + tabUid,
						'class': [ 'elementor-tab-title','elementor-tab-desktop-title' ],
						'data-tab': tabCount,
						'role': 'tab',
						'tabindex': 1 === tabCount ? '0' : '-1',
						'aria-controls': 'elementor-tab-content-' + tabUid,
						'aria-expanded': 'false',
						} );
					#>
						<div {{{ view.getRenderAttributeString( tabTitleKey ) }}}>{{{ item.tab_title }}}</div>
					<# } ); #>
				</div>
				<div class="elementor-tabs-content-wrapper">
					<# _.each( settings.tabs, function( item, index ) {
						var tabCount = index + 1,
							tabContentKey = view.getRepeaterSettingKey( 'tab_content', 'tabs',index );

						view.addRenderAttribute( tabContentKey, {
							'id': 'elementor-tab-content-' + elementUid + tabCount,
							'class': [ 'elementor-tab-content', 'elementor-clearfix', 'elementor-repeater-item-' + item._id ],
							'data-tab': tabCount,
							'role' : 'tabpanel',
							'aria-labelledby' : 'elementor-tab-title-' + elementUid + tabCount
						} );

						view.addInlineEditingAttributes( tabContentKey, 'advanced' ); #>
						<div class="elementor-tab-title elementor-tab-mobile-title" data-tab="{{ tabCount }}" role="tab">{{{ item.tab_title }}}</div>
						<div {{{ view.getRenderAttributeString( tabContentKey ) }}}>{{{ item.tab_content }}}</div>
					<# } ); #>
				</div>
			<# } #>
		</div>
		<?php
	}
}
