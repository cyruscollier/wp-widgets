<?php

namespace WordPress\Widgets;

/**
 * Widget Text Advanced class
 *
 * @author cyruscollier
 */
class TextAdvanced extends \WP_Widget
{

    function __construct()
    {
        $widget_ops = array('classname' => 'widget_text', 'description' => __('Arbitrary text or HTML'));
        $control_ops = array('width' => 400, 'height' => 350);
        parent::__construct('text_advanced', __('Text Advanced'), $widget_ops, $control_ops);
    }

    function widget( $args, $instance )
    {
        extract($args);
        $title = apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $hide_title = $instance['hide_title'];
        $text = apply_filters( 'widget_text', $instance['text'], $instance );
        $text = do_shortcode( $text );
        $css_class = $this->widget_options['classname'] . ' '. $instance['css_class'];
        $css_id_override = $instance['css_id_override'];
        $image_bg = $instance['image_bg'];
        $before_widget = str_replace($this->widget_options['classname'], trim($css_class), $before_widget);
        $replace = $this->id;
        if ( $css_id_override ) $replace = $css_id_override;
        if ( $image_bg ) $replace .= '" style="background-image:url('.$image_bg.');';
        $before_widget = str_replace($this->id, $replace, $before_widget);

        echo $before_widget;
        if ( !(empty( $title ) || $hide_title)  ) { echo $before_title . $title . $after_title; }
        ?>
        	
            <div class="textwidget"><?php echo $instance['filter'] ? wpautop($text) : $text; ?></div>
        <?php
        echo $after_widget;
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['hide_title'] = isset($new_instance['hide_title']);
        $instance['css_class'] = strip_tags($new_instance['css_class']);
        $instance['css_id_override'] = strip_tags($new_instance['css_id_override']);
        $instance['image_bg'] = strip_tags($new_instance['image_bg']);
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
            $instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) );
        $instance['filter'] = isset($new_instance['filter']);
        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'css_class' => '', 'css_id_override' => '' ) );
        $title = $instance['title'];
        $css_class = strip_tags($instance['css_class']);
        $css_id_override = strip_tags($instance['css_id_override']);
        $image_bg = strip_tags($instance['image_bg']);
        $text = format_to_edit($instance['text']);
?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
        <p><input id="<?php echo $this->get_field_id('hide_title'); ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" type="checkbox" <?php checked(isset($instance['hide_title']) ? $instance['hide_title'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hide_title'); ?>"><?php _e('Hide title for display'); ?></label></p>
        <p><label for="<?php echo $this->get_field_id('css_class'); ?>"><?php _e('CSS Class(es):'); ?></label><br />
        <input class="widefat"  class="" id="<?php echo $this->get_field_id('css_class'); ?>" name="<?php echo $this->get_field_name('css_class'); ?>" type="text" value="<?php echo esc_attr($css_class); ?>" /></p>
        <p><label for="<?php echo $this->get_field_id('css_id_override'); ?>"><?php _e('CSS ID Override:'); ?></label><br />
        <input class="" id="<?php echo $this->get_field_id('css_id_override'); ?>" name="<?php echo $this->get_field_name('css_id_override'); ?>" type="text" value="<?php echo esc_attr($css_id_override); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('image_bg'); ?>"><?php _e('Background Image:'); ?></label><br />
        <input class="" id="<?php echo $this->get_field_id('image_bg'); ?>" name="<?php echo $this->get_field_name('image_bg'); ?>" type="text" value="<?php echo esc_attr($image_bg); ?>" /></p>
        
        <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>

        <p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
<?php
    }

}