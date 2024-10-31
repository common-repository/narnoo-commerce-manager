<?php

if ( ! defined( 'ABSPATH' ) ) {

    exit;

}



if ( ! class_exists( 'WP_Importer' ) ) {

    return;

}



class NCM_Tax_Rate_Importer extends WP_Importer {



    public $ncm_tax_file_name = '';



    public function dispatch() {

        global $ncm_tax;

        if( isset( $_REQUEST['step'] ) && $_REQUEST['step'] == 1 ) {



            if ( $this->handle_upload() ) {



                if ( $this->ncm_tax_file_name ) {

                    $file = get_attached_file( $this->ncm_tax_file_name );

                } else {

                    $file = ABSPATH . $this->file_url;

                }



                $this->import( $file );

            }

        } else {



            echo '<div class="wrap">';

            echo '<h1>' . __( 'Import tax rates', NCM_txt_domain ) . '</h1>';



            echo '<div class="narrow">';

            echo '<p>' . __( 'Hi there! Upload a CSV file containing tax rates to import the contents into your shop. Choose a .csv file to upload, then click "Upload file and import".', NCM_txt_domain ) . '</p>';



            echo '<p>' .  __( 'Tax rates need to be defined with columns in a specific order (10 columns). ', NCM_txt_domain);

            echo '<a href="'.$ncm_tax->ncm_get_tax_rate_file_url().'">';

            _e('Click here to download a sample', NCM_txt_domain);

            echo '</a>.</p>';



            $action = 'admin.php?import=ncm_tax_rate_csv&step=1';



            $size = size_format( wp_max_upload_size() );

            $upload_dir = wp_upload_dir();

            ?>

            <form enctype="multipart/form-data" id="import-upload-form" method="post" action="<?php echo $action; ?>">

                <table class="form-table">

                    <tbody>

                        <tr>

                            <th>

                                <label for="upload"><?php _e( 'Choose a file from your computer:', NCM_txt_domain ); ?></label>

                            </th>

                            <td>

                                <input type="file" id="upload" name="import" size="25" />

                                <input type="hidden" name="action" value="save" />

                                <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />

                                <small><?php

                                    /* translators: %s: maximum upload size */

                                    printf(

                                        __( 'Maximum size: %s', NCM_txt_domain ),

                                        $size

                                    );

                                ?></small>

                            </td>

                        </tr>

                        <tr>

                            <th>

                                <label for="file_url"><?php _e( 'OR enter path to file:', NCM_txt_domain ); ?></label>

                            </th>

                            <td>

                                <?php echo ' ' . ABSPATH . ' '; ?><input type="text" id="file_url" name="file_url" size="25" />

                            </td>

                        </tr>

                        <tr>

                            <th><label><?php _e( 'Delimiter', NCM_txt_domain ); ?></label><br/></th>

                            <td><input type="text" name="delimiter" placeholder="," size="2" /></td>

                        </tr>

                    </tbody>

                </table>

                <p class="submit">

                    <input type="submit" class="button" value="<?php esc_attr_e( 'Upload file and import', NCM_txt_domain ); ?>" />

                </p>

            </form>

                <?php



            echo '</div>';

            echo "</div>";

        }

    }



    public function import( $file ) {

        global $ncm_tax, $ncm;

        if ( ! is_file( $file ) ) {

            $this->import_error( __( 'The file does not exist, please try again.', 'woocommerce' ) );

        }



        if ( function_exists( 'gc_enable' ) ) {

            gc_enable();

        }

        set_time_limit( 0 );

        @ob_flush();

        @flush();

        @ini_set( 'auto_detect_line_endings', '1' );



        $loop = 0;

        $delimiter = ( isset($_POST['delimiter']) && !empty($_POST['delimiter']) )? $_POST['delimiter']: ',';



        if ( ( $handle = fopen( $file, "r" ) ) !== false ) {



            $header = fgetcsv( $handle, 0, $delimiter );



            if ( 10 === sizeof( $header ) ) {



                while ( ( $row = fgetcsv( $handle, 0, $delimiter ) ) !== false ) {



                    list( $country, $state, $postcode, $city, $rate, $name, $priority, $compound, $shipping, $class ) = $row;



                    $tax_rate = array(

                        'tax_rate_country'  => $country,

                        'tax_rate_state'    => $state,

                        'tax_rate_postcode' => $postcode,

                        'tax_rate_city'     => $city,

                        'tax_rate_rate'     => $rate,

                        'tax_rate_name'     => $name,

                        'tax_rate_priority' => $priority,

                        'tax_rate_compound' => $compound ? 1 : 0,

                        'tax_rate_shipping' => $shipping ? 1 : 0,

                        'tax_rate_order'    => $loop ++,

                        'tax_rate_class'    => $class,

                    );



                    $ncm_tax->ncm_save_tax( $tax_rate );

                }

            } else {

                $this->import_error( __( 'The CSV is invalid.', NCM_txt_domain ) );

            }



            fclose( $handle );

        }



        // Show Result

        echo '<div class="updated settings-error"><p>';

        /* translators: %s: tax rates count */

        printf(

            __( 'Import complete - imported %s tax rates.', NCM_txt_domain ),

            '<strong>' . $loop . '</strong>'

        );

        echo '</p></div>';



        echo '<p>' . __( 'All done!', NCM_txt_domain );

        echo '<a href="' . admin_url( 'admin.php?page='.$ncm->ncm_setting.'&tab=tax' ) . '">';

        echo __( 'View tax rates', NCM_txt_domain ) . '</a>' . '</p>';

    }



    public function handle_upload() {

        if ( empty( $_POST['file_url'] ) ) {



            $file = wp_import_handle_upload();



            if ( isset( $file['error'] ) ) {

                $this->import_error( $file['error'] );

            }



            $this->ncm_tax_file_name = absint( $file['id'] );



        } elseif ( file_exists( ABSPATH . $_POST['file_url'] ) ) {

            $this->file_url = esc_attr( $_POST['file_url'] );

        } else {

            $this->import_error();

        }



        return true;

    }



    private function import_error( $message = '' ) {

        echo '<p><strong>' . __( 'Sorry, there has been an error.', 'woocommerce' ) . '</strong><br />';

        if ( $message ) {

            echo esc_html( $message );

        }

        echo '</p>';

        $this->footer();

        die();

    }

}

