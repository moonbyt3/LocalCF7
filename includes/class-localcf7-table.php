<?php

    class LocalCF7Table extends WP_List_Table {

        // function extra_tablenav( $which ) {
        //     switch ( $which ) {
        //         case 'top':
        //             echo 'top';
        //             break;

        //         case 'bottom':
        //             echo 'bottom';
        //             break;
        //     }
        // }
        
        function get_columns() {
            $columns = array(
                'title' => 'From Name',
                'postedAt' => 'Posted At',
                'formData' => 'Form Data'
                );
            return $columns;
           
        }

        function prepare_items() {
            global $wpdb;
    
            $table_name = $wpdb->prefix . "LocalCF7";
    
            $data = $wpdb->get_results( "SELECT * FROM $table_name" );

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = array();
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $data;
        }

        function column_default( $item, $column_name ) {
            $data = json_decode(json_encode($item), true); // converts stdClass to array

            switch( $column_name ) { 
              case 'title':
              case 'postedAt':
                return $data[ $column_name ];
                break;
              case 'formData':
                $form_data = unserialize($data[$column_name]);
                // print_r($form_data);
                $result = "";
                foreach($form_data as $form_row_key => $form_row_value) {
                    $result .= $form_row_key . ": " . $form_row_value . "<br>";
                }
                return $result;
              default:
                return print_r( $data, true ) ; //Show the whole array for troubleshooting purposes
            }
        }
    }
?>