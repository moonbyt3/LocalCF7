<?php

    class LocalCF7Table extends WP_List_Table {

        function extra_tablenav( $which ) {
            switch ( $which ) {
                case 'top':
                    echo 'top';
                    break;

                case 'bottom':
                    echo 'bottom';
                    break;
            }
        }

        function get_columns() {
            $columns = array(
                'name' => 'Name',
                'subject' => 'Subject'
                );
            return $columns;
           
        }

        function prepare_items() {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = array();
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $this->example_data;
        }
    }
?>