<?php
   /*
   Plugin Name: Woocommerce Hide Fields
   description: Hide Woocommerce checkout fields based on shipping method.
   Version: 1.0
   Author: Vinay Paudel
   Author URI: http://vinayp.com.np
   License: GPL2
   */
class Palki_Restaurant{

    public function __construct() 
    {

      add_filter( 'woocommerce_checkout_fields' , [ $this,'remove_address_field_for_pickup'],9999 );
        // The Jquery script
      add_action( 'wp_footer', [$this,'custom_checkout_script'] );
    }
   
    /**
    * WooCommerce Remove Address Fields from checkout for "Pick-Up" shipping method
    */
    Public  function remove_address_field_for_pickup( $fields ) 
    {

       global $woocommerce;

       $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
       $chosen_shipping = $chosen_methods[0];
         
       // only update fields if "local pick Up" shipping method is selected
       if( strpos($chosen_shipping, "local_pickup")!==FALSE ) {
            unset($fields['billing']['billing_address_1']['required']);
            unset($fields['billing']['billing_city']['required']);
            unset($fields['billing']['billing_postcode']['required']);
            unset($fields['billing']['billing_country']['required']);
            unset($fields['billing']['billing_state']['required']);

            array_push($fields['billing']['billing_address_1']['class'],'grve-hidden hidden');
            array_push($fields['billing']['billing_city']['class'],'grve-hidden hidden');
            array_push($fields['billing']['billing_country']['class'],'grve-hidden hidden');
            array_push($fields['billing']['billing_country']['class'],'grve-hidden hidden');
            array_push($fields['billing']['billing_state']['class'],'grve-hidden hidden');

          
        }
         
        return $fields;
    }
  
  public function custom_checkout_script() 
  {
    if( is_checkout() ):   
    
     // HERE your shipping methods rate IDs
    $local_pickup = 'local_pickup';
    $required_text = esc_attr__( 'required', 'woocommerce' );
    $required_html = '<abbr class="required" title="' . $required_text . '">*</abbr>';
    ?>
    <script type="text/javascript">
        jQuery(function($){
            var ism = 'input[name^="shipping_method"]',         ismc = ism+':checked',
                rq = '-required',       vr = 'validate'+rq,     w = 'woocommerce',      wv = w+'-validated',
                iv = '-invalid',        fi = '-field',          wir = w+iv+' '+w+iv+rq+fi,
                b = '#billing_',        s = '#shipping_',       f = '_field',
                a1 = 'country',     a2 = 'address_1',   a3 = 'city',   a4 = 'postcode',    a5 = 'state',
                b1 = b+a1+f,        b2 = b+a2+f,        b3 = b+a3+f,        b4 = b+a4+f,        b5 = b+a5+f,
                s1 = s+a1+f,        s2 = s+a2+f,        s3 = s+a3+f,        s4 = s+a4+f,        s5 = s+a5+f,
                localPickup = '<?php echo $local_pickup; ?>';

            // Utility function to shows or hide checkout fields
            function showHide( action='show', selector='' ){
                if( action == 'show' )
                    $(selector).show(function(){
                        $(this).addClass(vr);
                        $(this).removeClass(wv);
                        $(this).removeClass(wir);
                        if( $(selector+' > label > abbr').html() == undefined ){
                          $(selector+' > label > .optional').remove();
                          $(selector+' label').append('<?php echo $required_html; ?>');
                        }
                            
                    });
                else
                    $(selector).hide(function(){
                        $(this).removeClass(vr);
                        $(this).removeClass(wv);
                        $(this).removeClass(wir);
                        if( $(selector+' > label > abbr').html() != undefined )
                            $(selector+' label > .required').remove();
                    });
            }

            // Initializing at start after checkout init (Based on the chosen shipping method)
            setTimeout(function(){
                if( $(ismc).val().indexOf(localPickup) !== -1 )
                {
                    showHide('hide',b1);
                    showHide('hide',b2);
                    showHide('hide',b3);
                    showHide('hide',b4);
                    showHide('hide',b5);
                }
                else
                {
                    showHide('show',b1);
                    showHide('show',b2);
                    showHide('show',b3);
                    showHide('show',b4);
                    showHide('show',b5);
                    
                }
            }, 100);

            // When shipping method is changed (Live event)
            $( 'form.checkout' ).on( 'change', ism, function() {
                if( $(ismc).val().indexOf(localPickup) !== -1 )
                {
                    showHide('hide',b1);
                    showHide('hide',b2);
                    showHide('hide',b3);
                    showHide('hide',b4);
                    showHide('hide',b5);
                }
                else
                {
                    showHide('show',b1);
                    showHide('show',b2);
                    showHide('show',b3);
                    showHide('show',b4);
                    showHide('show',b5);
                    
                }
            });

            
        });
    </script>
    <?php
      endif;
    }

}

new Palki_Restaurant();



