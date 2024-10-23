<?php
if (!class_exists('WP_Customize_Control'))
    return NULL;

/**
 * Dropdown Select2 Custom Control
 *
 * @author Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link https://github.com/maddisondesigns
 */
class Newscrunch_Multiple_Category_Dropdown_Custom_Control extends WP_Customize_Control {

    /**
     * The type of control being rendered
     */
    public $type = 'dropdown_select2';

    /**
     * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
     */
    private $multiselect = false;

    /**
     * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
     */
    private $placeholder = 'Please select...';

    /**
     * Constructor
     */
    public function __construct($manager, $id, $args = array(), $options = array()) {
        parent::__construct($manager, $id, $args);
        // Check if this is a multi-select field
        if (isset($this->input_attrs['multiselect']) && $this->input_attrs['multiselect']) {
            $this->multiselect = true;
        }
        // Check if a placeholder string has been specified
        if (isset($this->input_attrs['placeholder']) && $this->input_attrs['placeholder']) {
            $this->placeholder = $this->input_attrs['placeholder'];
        }
    }

    /**
     * Enqueue our scripts and styles
     */
    public function enqueue() {
        wp_enqueue_script('newscrunch-multiple-category-select2-js', NEWSCRUNCH_TEMPLATE_DIR_URI . '/inc/customizer/custom-controls/multiple-category-dropdown/js/select2.full.min.js', array('jquery'), '4.0.13', true);
         wp_enqueue_script('newscrunch-multiple-category-custom-controls', NEWSCRUNCH_TEMPLATE_DIR_URI . '/inc/customizer/assets/js/customizer.js', array('newscrunch-multiple-category-select2-js'), '1.0', true);
        if(class_exists('Newscrunch_Plus'))
        {
         wp_enqueue_script('newscrunch-plus-multiple-category-custom-controls-js', NEWSCRUNCH_TEMPLATE_DIR_URI . '/inc/customizer/assets/js/customizer-plus.js', array('newscrunch-multiple-category-select2-js'), '1.0', true);
        }
        wp_enqueue_style('newscrunch-multiple-category-custom-controls-css', NEWSCRUNCH_TEMPLATE_DIR_URI . '/inc/customizer/custom-controls/multiple-category-dropdown/css/muliple-select-category.css', array(), '1.1', 'all');
        wp_enqueue_style('newscrunch-multiple-category-select2-css', NEWSCRUNCH_TEMPLATE_DIR_URI . '/inc/customizer/custom-controls/multiple-category-dropdown/css/select2.min.css', array(), '4.0.13', 'all');
    }

    /**
     * Render the control in the customizer
     */
    public function render_content() {
        $defaultValue = $this->value();
        
        if(is_array($defaultValue)){
            $defaultValue= implode(',', $defaultValue);
        }
        
        if ($this->multiselect) {
            $defaultValue = explode(',', $defaultValue);
        }

//        $this->choices=get_categories();
        $newscrunch_cats = get_categories();
        $newscrunch_CatIdName = array();
        foreach ($newscrunch_cats as $value) {
            $newscrunch_CatIdName[$value->term_id] = $value->name;
        }
        $this->choices = $newscrunch_CatIdName;
        ?>
        <div class="dropdown_select2_control">
            <?php if (!empty($this->label)) { ?>
                <label for="<?php echo esc_attr($this->id); ?>" class="customize-control-title">
                    <?php echo esc_html($this->label); ?>
                </label>
            <?php } ?>
            <?php if (!empty($this->description)) { ?>
                <span class="customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php } ?>
            <input type="hidden" id="<?php echo esc_attr($this->id); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr($this->value()); ?>" name="<?php echo esc_attr($this->id); ?>" <?php esc_attr ( $this->link() ); ?> />
            <select name="select2-list-<?php echo ( $this->multiselect ? 'multi[]' : 'single' ); ?>" class="customize-control-select2" data-placeholder="<?php echo $this->placeholder; ?>" <?php echo ( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
                <?php
                if (!$this->multiselect) {
                    // When using Select2 for single selection, the Placeholder needs an empty <option> at the top of the list for it to work (multi-selects dont need this)
                    echo '<option></option>';
                }
                foreach ($this->choices as $key => $value) {
                    if (is_array($value)) {
                        echo '<optgroup label="' . esc_attr($key) . '">';
                        foreach ($value as $optgroupkey => $optgroupvalue) {
                            if ($this->multiselect) {
                                echo '<option value="' . esc_attr($optgroupkey) . '" ' . ( in_array(esc_attr($optgroupkey), $defaultValue) ? 'selected="selected"' : '' ) . '>' . esc_attr($optgroupvalue) . '</option>';
                            } else {
                                echo '<option value="' . esc_attr($optgroupkey) . '" ' . selected(esc_attr($optgroupkey), $defaultValue, false) . '>' . esc_attr($optgroupvalue) . '</option>';
                            }
                        }
                        echo '</optgroup>';
                    } else {
                        if ($this->multiselect) {
                            echo '<option value="' . esc_attr($key) . '" ' . ( in_array(esc_attr($key), $defaultValue) ? 'selected="selected"' : '' ) . '>' . esc_attr($value) . '</option>';
                        } else {
                            echo '<option value="' . esc_attr($key) . '" ' . selected(esc_attr($key), $defaultValue, false) . '>' . esc_attr($value) . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </div>
        <?php
    }

}


/**
 * Multiple select customize control class.
 */
class Newscrunch_Post_Title_Multiple_Select extends WP_Customize_Control {

    /**
     * The type of customize control being rendered.
     */
    public $type = 'multiple-select';

    /**
     * Displays the multiple select on the customize screen.
     */
    public function render_content() {

    if ( empty( $this->choices ) )
        return;
    ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <select <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
                <?php
                    foreach ( $this->choices as $value => $label ) {
                        $selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
                        echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
                    }
                ?>
            </select>
        </label>
    <?php }
}