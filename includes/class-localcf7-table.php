<?php

    class LocalCF7Table extends WP_List_Table {

        function extra_tablenav( $which ) {
            switch ( $which ) {
                case 'top':
                    echo '<a href="https://decem.co" target="_blank" class="cf7-link">Developed with ❤️ by Decem</a>';
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

        function prepare_items() {
            global $wpdb;
    
            $table_name = $wpdb->prefix . "LocalCF7";
    
            $data = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY postedAt DESC" );

            $columns = $this->get_columns();
            $hidden = array();
            $sortable = array();
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $data;
        }

        private function format_form_data($str) {
            if (strpos($str, '<!DOCTYPE html>') !== false) {
                $d = new DOMDocument;
                $mock = new DOMDocument;
                $d->loadHTML($str);
                $body = $d->getElementsByTagName('body')->item(0);
                foreach ($body->childNodes as $child){
                    $mock->appendChild($mock->importNode($child, true));
                }

                $str = $mock->saveHTML();
            }
            return $str;
        }

        function column_default( $item, $column_name ) {
            
            $data = json_decode(json_encode($item), true); // converts stdClass to array
            $html = '';

            switch( $column_name ) { 
              case 'title':
              case 'postedAt':
                return $data[ $column_name ];
                break;
              case 'formData':
                $html .= '<div class="localcf7-table-form">';
                $html .= '<a href="' . plugin_dir_path() . '/single-item.php' . '" class="localcf7-table-form__btn">Show Form Data</a>';
                $html .= '<div class="localcf7-table-form-data">';
                    $html .= '<div class="localcf7-table-form-data-wrap">';
                        
                            $html .= '<span class="localcf7-table-form-data-label">' . $this->format_form_data($data[$column_name]) . "</span> ";
                        
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