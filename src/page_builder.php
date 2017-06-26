<?
namespace topshelfdesign;

class page_builder extends acf_template_loader
{

    public function __construct($vars)
    {
        parent::__construct($vars);

        $this->rows = get_field("builder_rows", $this->ID);


    }

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

            case 'quarter':
                $op = [9, 3];
                break;

            case 'third':
                $op = [8, 4];
                break;

            case 'half':
                $op = [6, 6];
                break;

            case 'two-thirds':
                $op = [4, 8];
                break;

            case 'three-quarters':
                $op = [3, 9];
                break;

            default:
                $op = [6, 6];
                break;


        }

        return $op;
    }

    public function pb_output()
    {

        foreach ($this->rows as $row):

            $primary = $row['primary_content'];
            $sidebar = $row['sidebar_content'];

            $this->division = $row['sidebar'];

            $this->columns = $this->get_division();

            $l_column_content = new page_builder();
            $l_column_content->use_custom_field_group($primary['layouts']);
            $l_column_op = $l_column_content->get_output();

            $r_column_content = new page_builder();
            $r_column_content->use_custom_field_group($sidebar['layouts']);
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
        endforeach;
    }

}