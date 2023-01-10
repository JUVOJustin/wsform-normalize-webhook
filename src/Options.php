<?php

namespace WSForm_Normalize_Webhook;

use WS_Form_Submit;

class Options
{

    /**
     * Register ACF Options Page
     *
     * @return void
     */
    public function register_page() {

        if (!function_exists('acf_add_options_page')) {
            return;
        }

        acf_add_options_page(array(
            'page_title'  => 'WSForm Normalize Webhook',
            'menu_title'  => 'WSForm Normalize Webhook',
            'menu_slug'   => 'wsform-nomalize-webhook',
            'parent_slug' => 'options-general.php',
            'capability'  => 'edit_posts',
            'redirect'    => false
        ));

    }

    /**
     * Register ACF Fields
     *
     * @return void
     */
    public function register_fields() {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group(array(
            'key'                   => 'group_636017172a823',
            'title'                 => 'Hook URL Mapping',
            'fields'                => array(
                array(
                    'key'               => 'field_63601751d963e',
                    'label'             => 'Mapping',
                    'name'              => 'mapping',
                    'type'              => 'repeater',
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => array(
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ),
                    'collapsed'         => '',
                    'min'               => 0,
                    'max'               => 0,
                    'layout'            => 'table',
                    'button_label'      => '',
                    'sub_fields'        => array(
                        array(
                            'key'               => 'field_63601771d963f',
                            'label'             => 'Hook',
                            'name'              => 'hook',
                            'type'              => 'select',
                            'instructions'      => '',
                            'required'          => 1,
                            'conditional_logic' => 0,
                            'wrapper'           => array(
                                'width' => '',
                                'class' => '',
                                'id'    => '',
                            ),
                            'choices'           => array(),
                            'default_value'     => false,
                            'allow_null'        => 0,
                            'multiple'          => 0,
                            'ui'                => 0,
                            'return_format'     => 'value',
                            'ajax'              => 0,
                            'placeholder'       => '',
                        ),
                        array(
                            'key'               => 'field_6360178bd9640',
                            'label'             => 'URL',
                            'name'              => 'url',
                            'type'              => 'url',
                            'instructions'      => '',
                            'required'          => 1,
                            'conditional_logic' => 0,
                            'wrapper'           => array(
                                'width' => '',
                                'class' => '',
                                'id'    => '',
                            ),
                            'default_value'     => '',
                            'placeholder'       => '',
                        ),
                    ),
                ),
            ),
            'location'              => array(
                array(
                    array(
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'wsform-nomalize-webhook',
                    ),
                ),
            ),
            'menu_order'            => 0,
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen'        => '',
            'active'                => true,
            'description'           => '',
            'show_in_rest'          => 0,
        ));
    }

    /**
     * Get alls hooks configured in wsform actions as select field option
     *
     * @param $field
     * @return array
     * @throws \Exception
     */
    public function get_actions($field) {

        if (!function_exists('wsf_form_get_all')) {
            return $field;
        }

        $forms = wsf_form_get_all();

        if (empty($forms)) {
            return $field;
        }

        foreach ($forms as &$form) {
            $form = wsf_form_get_form_object($form['id']);

            $actions = $form->meta->action->groups[0]->rows ?? [];
            foreach ($actions as $action) {
                $meta = $action->data[1] ?? [];
                $meta = json_decode($meta);

                if ($meta->id == "hook") {
                    $field['choices'][$meta->meta->action_hook_hook] = $meta->meta->action_hook_hook;
                }
            }
        }

        return $field;
    }

    /**
     * Dynamically register Webhooks if a url is mapped on options page
     *
     * @return void
     */
    public function register_webhooks() {
        $mapping = get_field('field_63601751d963e', 'options');
        if (empty($mapping)) {
            return;
        }

        foreach ($mapping as $map) {
            $hook = $map['hook'];
            $url = $map['url'];

            if (empty($hook) || empty($url)) {
                continue;
            }

            add_action($hook, function($form, WS_Form_Submit $submit) use ($url) {
                $webhook = new Webhook($url, $submit, $form);
                $webhook->send();
            }, 10, 2);
        }
    }
}