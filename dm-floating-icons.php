<?php/*    Plugin Name: Floating Icons    Description: Add icons to your site with an excerpt hover effect and link.    Author: Catapult Impact    Author URI: http://www.catapultimpact.com    Demo: http://catapultimpact.com/pagelines/floating-icons/	Pagelines: true*/class catapultimpactFloatingIcons{	//constructor for the class	function __construct(){		$this->base_url = sprintf( '%s/%s', WP_PLUGIN_URL,  basename(dirname( __FILE__ )));		$this->icon = $this->base_url . '/icon.png';		$this->base_dir = sprintf( '%s/%s', WP_PLUGIN_DIR,  basename(dirname( __FILE__ )));		$this->base_file = sprintf( '%s/%s/%s', WP_PLUGIN_DIR,  basename(dirname( __FILE__ )), basename( __FILE__ ));		// register plugin hooks...				$this->plugin_hooks();	}		//function to change hexadecimal value of color to rgb value	function hex2rgb( $colour ){        		if( $colour[0] == '#' ){						$colour = substr( $colour, 1 );        		}        		if( strlen( $colour ) == 6 ){						list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );        		}elseif( strlen( $colour ) == 3 ){						list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );        		}else{						return false;        		}        		$r = hexdec( $r );        		$g = hexdec( $g );        		$b = hexdec( $b );        		return array( 'red' => $r, 'green' => $g, 'blue' => $b );		}	// call to actions and hooks	function plugin_hooks(){		// Always run				add_action( 'after_setup_theme', array( &$this, 'options' ));				add_action( 'wp_enqueue_scripts', array( &$this, 'floatingicons_css' ) );			add_action( 'pagelines_page', array( &$this, 'floatingicons' ));	}	// including css and js files	function floatingicons_css() {		wp_register_style( 'floatingicons-style', plugins_url( 'css/maincss.css', __FILE__ ) );		wp_enqueue_style( 'floatingicons-style' );		wp_register_script( 'floatingicons-script', plugins_url('/css/jquery.qtip-1.0.0-rc3.min.js', __FILE__),array( 'jquery' ) );		wp_enqueue_script( 'floatingicons-script' );	}	// setting up the options value for plugin at DMS tester	function options(){		$options = array();		$options[] = array(			'key'		=> 'floatingicon_show_settings',			'type'		=> 'multi',			'title'		=> __('Floating Icons Main Options', 'pagelines'),			'col'		=> 1,			'opts'	=> array(				array(					'key'	=> 'show_floatingicon',					'type' 	=> 'check',					'label' => 'Activate',				),				array(					'key'	=> 'floatingicon_position_type',					'type' 	=> 'check',					'label' => 'Make Floating Icons Position Fixed?'				),								array(					'key'	=> 'floatingicon_hide_mob',					'type' 	=> 'check',					'label' => 'Hide Floating Icons on Mobile Devices?'				),				array(					'key'	=> 'floatingicon_orientation',					'type' 	=> 'select',					'label' => 'Select Floating Icons Orientation',					'opts'	=> array(						'right'	=> array('name'	=>'Right (Default)'),						'left'	=> array('name'	=>'Left'),					),					'default'	=> 'right',				),				array(					'key'	  => 'floatingicon_top_position',					'type'    => 'text',					'label'	  => 'Floating Icons top position.',										'help'	  => 'Top position should be set by pixels from the top of the browser window (e.g. 30px)'				),								array(					'key'	  => 'floatingicon_size',					'type'    => 'text',					'label'	  => 'Floating Icons Size.',					'help'	=> 'Size should be set by the icon images width in pixels (e.g. 40px)'				),				array(					'key'	  => 'floatingicon_bkgrnd_col',					'type'    => 'color',					'label'	  => 'Floating Icons Background Color.',					'help'	=> 'Leave blank if you dont want background'				),				array(					'key'	  => 'floatingicon_bkgrnd_op',					'type'    => 'text',					'label'	  => 'Floating Icons Background Opacity.',					'help'	=> 'Leave blank if you dont want background, or set value in percentage (e.g 70%)'				),			),		);		// options array for floating icons array shown as an accordion		$options[] = array(			'key'		=> 'floatingicons_array',	    	'type'		=> 'accordion',			'col'		=> 2,			'title'		=> __('Floating Icon Setup', 'pagelines'),			'post_type'	=> __('floating icon', 'pagelines'),			'opts'	=> array(				array(					'key'		=> 'title',					'label'		=> __( 'Floating Icon Title', 'pagelines' ),					'type'		=> 'text'				),				array(					'key'		=> 'text',					'label'	=> __( 'Floating Icon Text', 'pagelines' ),					'type'	=> 'textarea'				),				array(					'key'		=> 'link',					'label'		=> __( 'Floating Icon Link', 'pagelines' ),					'type'		=> 'text'				),				array(					'key'		=> 'newwindow',					'label'		=> __( 'Open in new window', 'pagelines' ),					'type'		=> 'check'				),				array(					'key'		=> 'icon',					'label'		=> __( 'Icon (Icon Mode)', 'pagelines' ),					'type'		=> 'select_icon'				),				array(					'key'		=> 'image',					'label'		=> __( 'Floating Icon Image (Image Mode)', 'pagelines' ),					'type'		=> 'image_upload'				),				array(					'key'		=> 'color',					'label'		=> __( 'Icon Color', 'pagelines' ),					'type'		=> 'color'				),			)	    );		$option_args = array(			'name'		=> 'Floating Icons',			'opts'		=> $options,			'icon'		=> 'icon-tag',			'pos'		=> 12		);		pl_add_options_page( $option_args );	}	// function that displays output of the plugin at front end	function floatingicons() {		// get the array of floating icons		$floatingicons_array = pl_setting('floatingicons_array');		// get the values of the options 		$show_floatingicon = (pl_setting('show_floatingicon')!='') ? pl_setting('show_floatingicon') : 1;		$floatingicon_position_type = (pl_setting('floatingicon_position_type')!='') ? pl_setting('floatingicon_position_type') : 2;		$floatingicons_orentation = (pl_setting('floatingicon_orientation')!='') ? pl_setting('floatingicon_orientation') : 'right';		$floatingicons_top = (pl_setting('floatingicon_top_position')!='') ? rtrim( pl_setting('floatingicon_top_position'),'px' ) : 30;			$floatingicons_size = (pl_setting('floatingicon_size')!='') ? rtrim( pl_setting('floatingicon_size'),'px' ) : 30;		$floatingicon_bkgrnd_col = (pl_setting('floatingicon_bkgrnd_col')!='') ? pl_setting('floatingicon_bkgrnd_col') : '';				if( $floatingicon_bkgrnd_col != '' ){					$floatingicon_bkgrnd_col_arr = array();					// convert hexadecimal value of color to rgb value			$floatingicon_bkgrnd_col_arr = $this->hex2rgb( $floatingicon_bkgrnd_col );					}				$floatingicon_bkgrnd_op_th = (pl_setting('floatingicon_bkgrnd_op')!='') ? rtrim( pl_setting('floatingicon_bkgrnd_op'),'%' ) : 70;				$floatingicon_bkgrnd_op = ( $floatingicon_bkgrnd_op_th / 100 );		$floatingicon_hide_mob = (pl_setting('floatingicon_hide_mob')!='') ? pl_setting('floatingicon_hide_mob') : 2;				$style_str = '';		if( is_array($floatingicons_array) ){			if($floatingicon_position_type == 1){				$positionType = 'position: fixed;';			}else{				$positionType = '';			}			if($show_floatingicon == 1){				if($floatingicons_orentation == 'right'){					if( $floatingicon_bkgrnd_col != '' ){											$style_str = 'background-color: rgba('.$floatingicon_bkgrnd_col_arr['red'].', '.$floatingicon_bkgrnd_col_arr['green'].', '.$floatingicon_bkgrnd_col_arr['blue'].', '.$floatingicon_bkgrnd_op.'); border-radius: 5px 0 0 5px; margin: 5px 0 5px 5px; padding: 5px;';										}					$tooltip = 'rightMiddle';					$target = 'leftMiddle';				}elseif($floatingicons_orentation == 'left'){					if( $floatingicon_bkgrnd_col != '' ){											$style_str = 'background-color: rgba('.$floatingicon_bkgrnd_col_arr['red'].', '.$floatingicon_bkgrnd_col_arr['green'].', '.$floatingicon_bkgrnd_col_arr['blue'].', '.$floatingicon_bkgrnd_op.'); border-radius: 0 5px 5px 0; margin: 5px 5px 5px 0; padding: 5px;';										}					$tooltip = 'leftMiddle';					$target = 'rightMiddle';				}				$floatingicons = count( $floatingicons_array );				$count = 1;								if( $floatingicon_hide_mob == 1 ){										?>											<style>														body.mobile #outer_social_div{																display: none !important;															}													</style>										<?php								}								// Actual html of the plugin at front end	?> 				<script>					jQuery(document).ready(function(){						jQuery('#social_img_cont').find('.social_img_div').each(function(){							var tooltip_str = jQuery(this).attr('t');							jQuery(this).qtip({							   content: tooltip_str,							   position: {								  corner: {									 tooltip: '<?php echo $tooltip; ?>',									 target: '<?php echo $target; ?>'								  }							   },							   show: {								  when: { event: 'mouseover' },								  ready: false							   },							   hide: {								  when: { event: 'mouseout' },								  ready: false							   },							   style: {								  border: {									 width: 2,									 radius: 1								  },								  padding: 5, 								  textAlign: 'center',								  tip: true,								  name: 'light'							   }							});						});					});				</script>			<div id="outer_social_div" style="position: relative;">					<div id="social_img_cont" style="<?php echo $style_str; ?> <?php echo $positionType; ?> top: <?php echo $floatingicons_top; ?>px; <?php echo $floatingicons_orentation;?>: 0;">		<?php 	foreach ($floatingicons_array as $floatingicon){							// get individual floating icon values 					$text = pl_array_get( 'text', $floatingicon, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.'); 					$title = pl_array_get( 'title', $floatingicon, 'floatingicon '. $count); 					$link = pl_array_get( 'link', $floatingicon ); 					$new_window = pl_array_get( 'newwindow', $floatingicon, 1 ); 					$image = pl_array_get( 'image', $floatingicon ); 					$icon = pl_array_get( 'icon', $floatingicon ); 					$color = pl_hash( pl_array_get( 'color', $floatingicon ), false);										$imgStr = '';					if($new_window == 1){						$open_in_new_window = 'target="_blank"';					}else{						$open_in_new_window = '';					}					if( !$image || $image == '' ){						if(!$icon || $icon == ''){							$icons = pl_icon_array();							$icon = $icons[ array_rand($icons) ];							$imgStr = '';						}					}elseif( isset($image) && $image != '' ){						$src = $image;						$imgStr = '<img style="width: '.$floatingicons_size.'px;" src="'.$src.'" />';						$icon = '';					}					$chkemailstr = strtolower($title);		?>					<div class="social_img_div" t="<?php echo $text; ?>">						<?php if($chkemailstr == "eemail"){							$str = '[pl_modal type="iii icon icon-3x icon-'.$icon.'" title="Join our email list for extra savings" label="'.$imgStr.'"][gravityform id="1" name="Join our email list for extra savings" title="false"][/pl_modal]';							echo do_shortcode($str);						}else{ ?>						<a style="color: <?php echo $color; ?>; font-size: <?php echo $floatingicons_size; ?>px !important;" class='iii icon icon-3x icon-<?php echo $icon; ?>' href="<?php echo $link; ?>" id="social_img_link" <?php echo $open_in_new_window; ?> >							<?php echo $imgStr; ?>						</a>						<?php } ?>					</div>		<?php 					}		?>					<div style="clear: both;"></div>				</div>			</div>			<div style="clear: both;"></div>	<?php			}		}	}}new catapultimpactFloatingIcons;?>