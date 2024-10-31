<?php
namespace RadiantRegistration\Fields\Traits;

trait Textoption {
    
    /**
     * default text option settings
     * 
     * @param boolean $word_restriction word_restriction.
     * 
     * @return array
     */
    public static function get_default_text_option_settings( $word_restriction = false ) {
        $properties = array(
            array(
                'name'      => 'placeholder',
                'title'     => __( 'Placeholder text', 'radiant-registration' ),
                'type'       => 'text',
                'tag_filter' => 'no_fields', // we don't want to show any fields with merge tags, just basic tags
                'section'    => 'advanced',
                'priority'   => 10,
                'help_text'  => __( 'Text for HTML5 placeholder attribute', 'radiant-registration' ),
            ),

            array(
                'name'       => 'default',
                'title'      => __( 'Default value', 'radiant-registration' ),
                'type'       => 'text_with_tag',
                'tag_filter' => 'no_fields',
                'section'    => 'advanced',
                'priority'   => 11,
                'help_text'  => __( 'The default value this field will have', 'radiant-registration' ),
            ),

            array(
                'name'      => 'size',
                'title'     => __( 'Size', 'radiant-registration' ),
                'type'      => 'text',
                'variation' => 'number',
                'section'   => 'advanced',
                'priority'  => 20,
                'help_text' => __( 'Size of this input field', 'radiant-registration' ),
            ),
        );

        if ( $word_restriction ) {
            $properties[] = array(
                'name'      => 'word_restriction',
                'title'     => __( 'Word Restriction', 'radiant-registration' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 15,
                'help_text' => __( 'Numebr of words the author to be restricted in', 'radiant-registration' ),
            );
        }

        return apply_filters( 'radiant_registration-form-builder-common-text-fields-properties', $properties );
    }
}

trait DropDownOption {

    /**
     * default dropdown option settings
     * 
     * @param boolean $is_multiple is_multiple.
     * 
     * @return array
     */
    public function get_default_option_dropdown_settings( $is_multiple = false ) {
        return array(
            'name'        => 'options',
            'title'       => __( 'Options', 'radiant-registration' ),
            'type'        => 'option_data',
            'is_multiple' => $is_multiple,
            'section'     => 'basic',
            'priority'    => 12,
            'help_text'   => __( 'Add options for the form field', 'radiant-registration' ),
        );
    }
}

trait TextareaOption {

    /**
     * default textarea option settings
     * 
     * @return array
     */
    public function get_default_textarea_option_settings() {
        return array(
            array(
                'name'      => 'rows',
                'title'     => __( 'Rows', 'radiant-registration' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 10,
                'help_text' => __( 'Number of rows in textarea', 'radiant-registration' ),
            ),

            array(
                'name'      => 'cols',
                'title'     => __( 'Columns', 'radiant-registration' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 11,
                'help_text' => __( 'Number of columns in textarea', 'radiant-registration' ),
            ),

            array(
                'name'         => 'placeholder',
                'title'        => __( 'Placeholder text', 'radiant-registration' ),
                'type'         => 'text',
                'section'      => 'advanced',
                'priority'     => 12,
                'help_text'    => __( 'Text for HTML5 placeholder attribute', 'radiant-registration' ),
                'dependencies' => array(
                    'rich' => 'no',
                ),
            ),

            array(
                'name'      => 'default',
                'title'     => __( 'Default value', 'radiant-registration' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 13,
                'help_text' => __( 'The default value this field will have', 'radiant-registration' ),
            ),

            array(
                'name'      => 'word_restriction',
                'title'     => __( 'Word Restriction', 'radiant-registration' ),
                'type'      => 'text',
                'section'   => 'advanced',
                'priority'  => 15,
                'help_text' => __( 'Numebr of words the author to be restricted in', 'radiant-registration' ),
            ),
        );
    }
}
