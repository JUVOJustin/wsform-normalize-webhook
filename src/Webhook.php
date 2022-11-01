<?php

namespace WSForm_Normalize_Webhook;

use stdClass;
use WS_Form_Submit;

class Webhook
{

    /**
     * @var string
     */
    private $url;

    /**
     * @var WS_Form_Submit
     */
    private $submit;

    /**
     * @var stdClass
     */
    private $form;

    /**
     * @param string $url
     * @param WS_Form_Submit $submit
     * @param $form
     */
    public function __construct(string $url, WS_Form_Submit $submit, $form) {
        $this->url = $url;
        $this->submit = $submit;
        $this->form = $form;
    }

    public function send() {
        $body = $this->format_body();
        $body = wp_json_encode($body);

        wp_remote_post($this->url, [
            'body'        => $body,
            'headers'     => [
                'Content-Type' => 'application/json',
            ],
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'httpversion' => '1.0',
            'sslverify'   => false,
            'data_format' => 'body',
        ]);
    }

    private function format_body() {

        $types_to_ignore = ['html', 'tab_next', 'tab_previous', 'message'];

        $fieldsInfo = [];
        $repeatableSections = [];

        foreach ($this->form->groups as $group) {
            foreach ($group->sections as $section) {

                // Separate sections if repeatable
                if ($section->meta->section_repeatable) {
                    $repeatableSections['section_' . $section->id] = [
                        "label" => $section->label,
                        "group" => $group->label,
                    ];
                }

                foreach ($section->fields as $field) {

                    // Skip fields with types to ignore
                    if (in_array($field->type, $types_to_ignore)) {
                        continue;
                    }

                    $fieldsInfo[intval($field->id)] = [
                        "label"   => $field->label,
                        "section" => $section->label,
                        "group"   => $group->label,
                        "type"    => $field->type
                    ];
                }

            }

        }

        // Prepare submit fields
        $meta_fields = $this->submit->meta;
        unset($meta_fields['wsf_meta_key_hidden']);

        $fields = [];
        foreach ($meta_fields as $key => $meta_field) {

            $id = $meta_field['id'];
            $row_index = $meta_field['repeatable_index'];
            $section_id = $meta_field['section_id'];

            // Add fields to sections
            if ($section_id && $row_index) {
                $repeatableSections['section_' . $section_id]['rows'][$row_index][$id] = $fieldsInfo[$id];
                $repeatableSections['section_' . $section_id]['rows'][$row_index][$id]['value'] = $meta_field['value_array'] ?? $meta_field['value'];

                // Remove duplicate data for sections
                unset($repeatableSections['section_' . $section_id]['rows'][$row_index][$id]['section']);
                unset($repeatableSections['section_' . $section_id]['rows'][$row_index][$id]['group']);
                continue;
            }

            // Merge fieldinfo with value
            $fields[$id] = $fieldsInfo[$id];
            $fields[$id]['value'] = wsf_submit_get_value($this->submit, $key);
        }

        return array_merge($fields, $repeatableSections);

    }

}