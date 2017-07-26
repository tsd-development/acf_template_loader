<?

namespace topshelfdesign;

class page_builder extends acf_template_loader
{

    public function __construct($vars = [])
    {
        parent::__construct($vars);

        $defaults = [
            'row_title' => 'builder_rows',
            'content_field_title' => 'layouts',
            'primary_content_title' => 'primary_content',
            'sidebar_content_title' => 'sidebar_content'
        ];

        $config = \wp_parse_args($vars, $defaults);

        $this->config = $config;

        $this->rows = get_field($config['row_title'], $this->ID);

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

        if (!count($this->rows)) {
            print "<h2>No Rows Found</h2>";
            return;
        }

        foreach ($this->rows as $row):

            $primary = $row[$this->config['primary_content_title']];
            $sidebar = $row[$this->config['sidebar_content_title']];

            $primary_with_labels = [];
            $sidebar_with_labels = [];

            if ($primary)
                foreach ($primary as $p_row):
                    $p_row[0]['page_builder'] = true;
                    $updated_row = $p_row;
                    $primary_with_labels[] = $updated_row;
                endforeach;


            if ($sidebar[$this->config['content_field_title']])
                foreach ($sidebar[$this->config['content_field_title']] as $s_row):
                    $s_row['page_builder'] = true;
                    $updated_row = $s_row;
                    $sidebar_with_labels[] = $updated_row;
                endforeach;

            foreach ($sidebar as $s_row) $s_row['page_builder'] = true;

            $this->division = $row['sidebar'];

            $this->columns = $this->get_division();

            $l_column_content = new page_builder();
            $l_column_content->use_custom_field_group($primary_with_labels[0]);
            $l_column_op = $l_column_content->get_output();

            $r_column_content = new page_builder();
            $r_column_content->use_custom_field_group($sidebar_with_labels);
            $r_column_op = $r_column_content->get_output();

            $vertical_align = $row['vertical_alignment'] ? $row['vertical_alignment'] : 'top';

            $op = $this->division != 'none' ? "
                <div class='row pb_builder_output align-{$vertical_align}'>
                    <div class='small-12 medium-{$this->columns[0]} column'>
                        {$l_column_op}
                    </div>
                    <div class='small-12 medium-{$this->columns[1]} column'>
                        {$r_column_op}
                    </div>
                </div>
            " : "
                <div class='row pb_builder_output'>
                    <div class='small-12 column'>
                        {$l_column_op}
                    </div>
                </div>
            ";

            print $op;
        endforeach;
    }

}