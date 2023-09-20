<?php 

class CustomFields {
    private $page_id;
    private $custom_fields;

    public function __construct($page_id, $custom_fields) {
        $this->page_id = $page_id;
        $this->custom_fields = $custom_fields;

        add_action('add_meta_boxes', array($this, 'add_custom_fields'));
        add_action('save_post', array($this, 'save_custom_fields'));
    }

    public function add_custom_fields() {
        global $post;

        if ($post->ID == $this->page_id) {
            foreach ($this->custom_fields as $field) {
                add_meta_box(
                    $field['id'],
                    $field['label'],
                    array($this, 'display_custom_field'),
                    'page',
                    'normal',
                    'default',
                    array('field' => $field)
                );
            }
        }
    }


    public function display_custom_field($post, $metabox) {
        $field = $metabox['args']['field'];
        $field_value = get_post_meta($post->ID, $field['id'], true);

        if ($field['type'] === 'text') {
            echo '<input type="text" name="' . esc_attr($field['id']) . '" value="' . esc_attr($field_value) . '" />';
        } elseif ($field['type'] === 'textarea') {
            echo '<textarea name="' . esc_attr($field['id']) . '">' . esc_textarea($field_value) . '</textarea>';
        }
    }

    public function save_custom_fields($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        foreach ($this->custom_fields as $field) {
            $field_id = $field['id'];

            if (isset($_POST[$field_id])) {
                update_post_meta($post_id, $field_id, sanitize_text_field($_POST[$field_id]));
            } else {
                delete_post_meta($post_id, $field_id);
            }
        }
    }
}

// Define custom fields for the contact page
$page_id = 13;
$custom_fields = array(
    array(
        'id' => 'contact_number',
        'label' => 'Contact Number',
        'type' => 'text',
    ),
    array(
        'id' => 'iframe_code',
        'label' => 'iFrame Code',
        'type' => 'textarea',
    ),
    array(
        'id' => 'address',
        'label' => 'Address',
        'type' => 'textarea',
    ),
    array(
        'id' => 'country',
        'label' => 'Country',
        'type' => 'text',
    ),
    array(
        'id' => 'address2',
        'label' => 'Address 2',
        'type' => 'text',
    ),
);

$contact_fields = new CustomFields($page_id, $custom_fields);
//
$page_id = 7;
$custom_fields = array(
    array(
        'id' => 'heading',
        'label' => 'Heading',
        'type' => 'text',
    ),
    array(
        'id' => 'sub_heading',
        'label' => 'Sub Heading',
        'type' => 'text',
    )
);

$about_fields = new CustomFields($page_id, $custom_fields);
//
$page_id = 9;
$custom_fields = array(
    array(
        'id' => 'about_sub_heading',
        'label' => 'Sub Heading',
        'type' => 'text',
    )
);

$about_fields = new CustomFields($page_id, $custom_fields);
