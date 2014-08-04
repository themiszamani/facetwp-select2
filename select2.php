<?php

class FacetWP_Facet_Select2
{

    function __construct() {
        $this->label = __( 'Select2', 'fwp' );
    }


    /**
     * Load the available choices
     */
    function load_values( $params ) {

        // Inherit the load_values() method from the Dropdown facet type
        $dropdown_facet = FWP()->helper->facet_types['dropdown'];
        return $dropdown_facet->load_values( $params );
    }


    /**
     * Generate the facet HTML
     */
    function render( $params ) {

        $output = '';
        $facet = $params['facet'];
        $values = (array) $params['values'];
        $selected_values = (array) $params['selected_values'];

        $output .= '<select class="facetwp-select2" multiple="multiple">';
        $output .= '<option value="">- ' . __( 'Any', 'fwp' ) . ' -</option>';

        foreach ( $values as $result ) {
            $selected = in_array( $result->facet_value, $selected_values ) ? ' selected' : '';
            $display_value = "$result->facet_display_value ($result->counter)";
            $output .= '<option value="' . $result->facet_value . '"' . $selected . '>' . $display_value . '</option>';
        }

        $output .= '</select>';
        return $output;
    }


    /**
     * Filter the query based on selected values
     */
    function filter_posts( $params ) {
        global $wpdb;

        $facet = $params['facet'];
        $selected_values = (array) $params['selected_values'];
        $selected_values = implode(',', $selected_values );

        $sql = "
        SELECT DISTINCT post_id FROM {$wpdb->prefix}facetwp_index
        WHERE facet_name = '{$facet['name']}' AND facet_value IN ('$selected_values')";
        return $wpdb->get_col( $sql );
    }


    /**
     * Output any admin scripts
     */
    function admin_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/load/select2', function($this, obj) {
        $this.find('.facet-source').val(obj.source);
        $this.find('.type-select2 .facet-orderby').val(obj.orderby);
        $this.find('.type-select2 .facet-count').val(obj.count);
    });

    wp.hooks.addFilter('facetwp/save/select2', function($this, obj) {
        obj['source'] = $this.find('.facet-source').val();
        obj['orderby'] = $this.find('.type-select2 .facet-orderby').val();
        obj['count'] = $this.find('.type-select2 .facet-count').val();
        return obj;
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output any front-end scripts
     */
    function front_scripts() {
?>
<script>
(function($) {
    wp.hooks.addAction('facetwp/refresh/select2', function($this, facet_name) {
        $this.find('.facetwp-select2').select2('destroy');
        FWP.facets[facet_name] = $this.find('.facetwp-select2').val() || [];
    });

    wp.hooks.addAction('facetwp/ready', function() {
        $(document).on('change', '.facetwp-facet .facetwp-select2', function() {
            FWP.autoload();
        });
    });

    $(document).on('facetwp-loaded', function() {
        $('.facetwp-select2').select2({
            width: 'element'
        });
    });
})(jQuery);
</script>
<?php
    }


    /**
     * Output admin settings HTML
     */
    function settings_html() {
?>
        <tr class="facetwp-conditional type-select2">
            <td><?php _e('Sort by', 'fwp'); ?>:</td>
            <td>
                <select class="facet-orderby">
                    <option value="count">Facet Count</option>
                    <option value="display_value">Display Value</option>
                    <option value="raw_value">Raw Value</option>
                </select>
            </td>
        </tr>
        <tr class="facetwp-conditional type-select2">
            <td>
                <?php _e('Count', 'fwp'); ?>:
                <span class="icon-question" title="The number of items to show">?</span>
            </td>
            <td><input type="text" class="facet-count" value="10" /></td>
        </tr>
<?php
    }
}
