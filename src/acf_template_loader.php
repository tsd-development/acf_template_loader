<?php

namespace topshelfdesign;

class acf_template_loader
{

    public function __construct($vars = false)
    {

        $this->id = $vars['ID'] ? $vars['ID'] : false;
        $this->acf_field_name = $vars['acf_field_name'] ? $vars['acf_field_name'] : 'content';
        $this->toc_title = $vars['toc_title'] ? $vars['toc_title'] : 'title';

        $this->fields = get_field($this->acf_field_name, $this->id);

        foreach($this->fields as $c => $field)
            $this->toc[$c] = $field[$this->toc_title];

    }

    public function update_field_title($title = false){

      if(!$title) return false;

      $this->acf_field_name = $title;

    }

    public function use_custom_field_group($field = false){

        if(!$field) return false;

        $this->fields = $field;

    }

    public function output()
    {
        foreach ($this->fields as $field):
            if (!file_exists(locate_template("acf-flexible-content/" . $this->acf_field_name . "/" . $field['acf_fc_layout'] . ".php"))) {
                if (is_user_logged_in())
                    print "<div class='callout alert'><p>File \"acf-flexible-content/{$this->acf_field_name}/{$field['acf_fc_layout']}.php\" not found, please contact the website admin.</p></div>";
            } else {
                include(locate_template("acf-flexible-content/" . $this->acf_field_name . "/" . $field['acf_fc_layout'] . ".php"));
            }
        endforeach;
    }

    static function get_template_use_info($acf_field_name = false){

        if(!$acf_field_name):
            print 'need field name';
            return false;
        endif;


        $query = new \WP_Query([
            'post_type' => 'page',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => $acf_field_name,
                    'compare' => 'EXISTS'
                ]
            ]
        ]);

        $op = [];

        foreach($query->posts as $post):
            $fields = get_field($acf_field_name, $post->ID);
            if(!$fields) continue;
            foreach($fields as $field):
                $op[$field['acf_fc_layout']][] = [
                    'title' => $post->post_title,
                    'ID' => $post->ID,
                    'link' => \get_permalink($post->ID)
                ];
            endforeach;

        endforeach;

        return $op;


    }


}
