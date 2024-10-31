<div class="respek_container respek_container_default" data-cart="<?php echo esc_attr($cart) ?>">

    <span class="respek_checkbox_container respek_checkbox_container_default <?php echo esc_attr($respek_session_opted == 1 ? 'selected' : 'unselected') ?>">


        <?php
        woocommerce_form_field('respek_offset', array(
            'type' => 'checkbox',
            'id' => 'respek_offset',
            'class' => array('respek_offset'), //'form-control',
            'required' => false,
        ), $respek_session_opted == 1 ? 1 : 0);

        ?>

        <div id="checkbox_label">
                <div class="inner_checkbox_label inner_checkbox_label_default respek_global_temp" id="default_respek_temp">


                    <span class="make make_respek_default">
                        <?php echo sprintf(__('Add %s to offset carbon & plant a spekboom.', 'respek-nature'), $currency_symbol . $surcharge); ?>
                    </span>
                    <input type="hidden" id="collections_state" value="<?php echo esc_attr(get_option('respek_on_us_collections')); ?>">
                    <span class="tooltip">
                        <?php
                        echo respek_nature\Components\respek_HelperComponent::RenderImage('images/respek_logo_dark.png', 'respek_logo', 'respek_logo_default', 'respek_button', 'skip-lazy');
                        $priceArr = str_split($surcharge);
                        $price_length = count($priceArr);
                        ?>
                        <div class="tooltip__content">
                            <div class="how">

                                <div class="co2-release co2-step">
                                    <div class="v56_10"></div>
                                    <span class="v33_20">Every time you purchase something, a small amount of CO₂ is released
                                        into the atmosphere.
                                    </span>

                                    <div class="v33_26 co2-arrow"></div>
                                </div>
                                <div class="co2-offset co2-step">
                                    <div class="v56_6"></div>
                                    <span class="v33_21">By supporting ReSpek Nature, you can plant a spekboom in the Karoo, or
                                        several of them, to offset your CO₂.</span>

                                    <div class="v33_27 co2-arrow"></div>

                                </div>

                                <div class="co2-reversed co2-step">
                                    <div class="v56_12"></div>
                                    <span class="v33_23">Result, the environmental impacts of consumption are reversed.</span>

                                    <div class="v33_28 co2-arrow"></div>

                                </div>
                            </div>
                            <p><a href="https://www.respeknature.org" target="_blank">learn more</a></p>
                        </div>
                    </span>
                </div>
        </div>

    </span>


</div>