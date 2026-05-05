<?php

namespace WSForm_Normalize_Webhook;

use WS_Form_Submit;

class Options
{
    const HOOK_CHOICES_TRANSIENT = 'wsform_normalize_webhook_hook_choices';
    const HOOK_CHOICES_TRANSIENT_TTL = 7 * \DAY_IN_SECONDS;

    /**
     * Track if get_actions is currently running to prevent recursive calls
     *
     * @var bool
     */
    private static $getting_actions = false;

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
     * Get all hooks configured in WS Form actions as select field options.
     *
     * @param array $field
     * @return array
     */
    public function get_actions($field) {

        // Prevent recursive calls that cause infinite loops
        // This happens when WS Form's Data Source Term calls acf_get_fields() during config
        if (self::$getting_actions) {
            return $field;
        }

        if (!$this->is_settings_page()) {
            return $field;
        }

        $choices = $this->get_cached_hook_choices();
        $saved_choices = $this->get_saved_hook_choices();

        $field['choices'] = $choices + $saved_choices;

        return $field;
    }

    /**
     * Clear cached WS Form hook choices when forms change.
     *
     * @return void
     */
    public function clear_hook_choices_cache() {
        delete_transient(self::HOOK_CHOICES_TRANSIENT);
    }

    /**
     * Keep WS Form discovery off regular requests.
     *
     * @return bool
     */
    private function is_settings_page() {
        if (!is_admin()) {
            return false;
        }

        $page = '';
        if (isset($_GET['page']) && is_string($_GET['page'])) {
            $page = sanitize_key(wp_unslash($_GET['page']));
        }

        return $page === 'wsform-nomalize-webhook';
    }

    /**
     * Read hook choices from cache or rebuild them immediately on a cache miss.
     *
     * @return array
     */
    private function get_cached_hook_choices() {
        $choices = get_transient(self::HOOK_CHOICES_TRANSIENT);
        if (is_array($choices)) {
            return $choices;
        }

        $choices = $this->build_hook_choices();
        if (is_array($choices)) {
            set_transient(self::HOOK_CHOICES_TRANSIENT, $choices, self::HOOK_CHOICES_TRANSIENT_TTL);
            return $choices;
        }

        return [];
    }

    /**
     * Build hook choices from WS Form only when the admin select needs them.
     *
     * @return array|null
     */
    private function build_hook_choices() {
        if (!function_exists('wsf_form_get_all') || !function_exists('wsf_form_get_form_object')) {
            return null;
        }

        self::$getting_actions = true;

        try {
            $forms = wsf_form_get_all();
            if (empty($forms)) {
                return [];
            }

            $choices = [];

            foreach ($forms as $form_data) {
                $form = wsf_form_get_form_object($form_data['id']);

                if (!$form || !isset($form->meta) || !isset($form->meta->action)) {
                    continue;
                }

                $actions = $form->meta->action->groups[0]->rows ?? [];
                foreach ($actions as $action) {
                    $meta = $action->data[1] ?? [];
                    $meta = json_decode($meta);

                    if ($meta && isset($meta->id) && $meta->id == "hook" && isset($meta->meta->action_hook_hook)) {
                        $choices[$meta->meta->action_hook_hook] = $meta->meta->action_hook_hook;
                    }
                }
            }

            return $choices;
        } catch (\Exception $exception) {
            return null;
        } finally {
            self::$getting_actions = false;
        }
    }

    /**
     * Preserve saved hook values even if WS Form discovery is unavailable.
     *
     * @return array
     */
    private function get_saved_hook_choices() {
        if (!function_exists('get_field')) {
            return [];
        }

        self::$getting_actions = true;

        try {
            $mapping = get_field('field_63601751d963e', 'options', false);
        } catch (\Exception $exception) {
            return [];
        } finally {
            self::$getting_actions = false;
        }

        if (empty($mapping) || !is_array($mapping)) {
            return [];
        }

        $choices = [];

        foreach ($mapping as $map) {
            if (!is_array($map)) {
                continue;
            }

            $hook = $map['hook'] ?? $map['field_63601771d963f'] ?? '';
            if (empty($hook)) {
                continue;
            }

            $choices[$hook] = $hook;
        }

        return $choices;
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

            add_action($hook, function($form, \WS_Form_Submit $submit) use ($url) {
                $webhook = new Webhook($url, $submit, $form);
                $webhook->send();
            }, 10, 2);
        }
    }
}
