<?
namespace topshelfdesign;

class page_builder extends acf_template_loader
{

    public function get_output()
    {
        ob_start();
        $this->output();
        $op = ob_get_contents();
        ob_end_clean();
        return $op;
    }

    private function get_division()
    {

        $op = [];

        switch ($this->division) {

            case 'quarter_left':
                $op = [3,9];
                break;

            case 'third_left':
                $op = [4,8];
                break;

            case 'half':
                $op = [6,6];
                break;

            case 'two_thirds_left':
                $op = [8,4];
                break;

            case 'three_quarters_left':
                $op = [9,3];
                break;

            default:
                $op = [6, 6];
                break;


        }

        return $op;
    }

    public function pb_output()
    {


        $primary = get_field("primary_content", $this->ID);
        $sidebar = get_field("sidebar_content", $this->ID);

        $this->division = get_field("column_division", $this->ID);

        $this->columns = $this->get_division();

        $l_column_content = new page_builder();
        $l_column_content->use_custom_field_group($primary['content']);
        $l_column_op = $l_column_content->get_output();

        $r_column_content = new page_builder();
        $r_column_content->use_custom_field_group($sidebar['content']);
        $r_column_op = $r_column_content->get_output();


        $op = "
        
        <div class='row pb_builder_output'>
            <div class='small-12 medium-{$this->columns[0]} column'>
                {$l_column_op}
            </div>
            <div class='small-12 medium-{$this->columns[1]} column'>
                {$r_column_op}
            </div>
        </div>
            
        
        ";

        print $op;
    }

}