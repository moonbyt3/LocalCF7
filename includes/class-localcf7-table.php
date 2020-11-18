<?php

    class LocalCF7Table extends WP_List_Table {

        function extra_tablenav( $which ) {
            switch ( $which ) {
                case 'top':
                    echo 'top';
                    break;

                case 'bottom':
                    echo "
                    <script>
                
                            var showFormButtons = jQuery('.localcf7-table-form__btn');
                            if (showFormButtons.length > 0) {
                                showFormButtons.each(function (i, element) {
                                    jQuery(element).on('click', function() {
                                        console.log(this);
                                        if (!jQuery(this).parent().hasClass('localcf7-table-form--active')) {
                                            jQuery(this).parent().addClass('localcf7-table-form--active');
                                            jQuery(this).text('Hide Form Data');
                                        } else {
                                            jQuery(this).parent().removeClass('localcf7-table-form--active');
                                            jQuery(this).text('Show Form Data');
                                        }
                                    });
                                });
                            }

                    </script>
                    ";
                    break;
            }
        }
        
        public function get_columns() {
            $columns = array(
                'title' => __('From Name', 'localCF7'),
                'postedAt' => __('Posted At', 'localCF7'),
                'formData' => __('Form Data', 'localCF7')
            );
            return $columns;
           
        }

        /**
         * Columns to make sortable.
         *
         * @return array
         */
        public function get_sortable_columns(){
            return array('title' => array('title', false));
        }

        /**
        * Render the bulk edit checkbox
        *
        * @param array $item
        *
        * @return string
        */
        public function column_cb( $item ) {
            return sprintf(
                '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['user_id']
            );
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

        private function strip_slashes_and_capitalize($str) {
            return ucwords(implode(' ', explode('-', $str)));
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
                $html .= '<div class="localcf7-table-form">';
                $html .= '<button class="localcf7-table-form__btn">Show Form Data</button>';
                $html .= '<div class="localcf7-table-form-data">';
                    $html .= '<div class="localcf7-table-form-data-wrap">';
                        foreach($form_data as $form_row_key => $form_row_value) {
                            $html .= '<span class="localcf7-table-form-data-label">' . $this->strip_slashes_and_capitalize($form_row_key) . ":</span> " . $form_row_value . "<br>";
                        }
                        $html .= '</div>';
                    $html .= '</div>';
                $html .= '</div>';
                return $html;
              default:
                return print_r( $data, true ) ; //Show the whole array for troubleshooting purposes
            }
        }
    }
?>