<?php

namespace topshelfdesign;

class acf_template_loader{

  public function __construct($id = false, $acf_field_name = 'content')
      {

        $this->id = $id;
        $this->acf_field_name = $acf_field_name;

          $fields = $sidebar ? get_field("sidebar_content", $id) : get_field("content", $id);

          $fields = get_field($this->acf_field_name, $this->id);

          if (!$fields) return false;
    
          foreach ($fields as $field):
              if (!file_exists(locate_template("acf-flexible-content-sidebar/" . $field['acf_fc_layout'] . ".php"))) {
                  if (is_user_logged_in())
                      print "<h2>Sidebar File \"{$field['acf_fc_layout']}.php\" not found, please contact the website admin.</h2>";
              } else {
                  include(locate_template("acf-flexible-content-sidebar/" . $field['acf_fc_layout'] . ".php"));
              }
          endforeach;

      }

}
