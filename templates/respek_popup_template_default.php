<div id="overlay"></div> 
<!-- new popup -->
<div class=" pico-content " style="position: fixed;
    z-index: 2147483647;
    left: 50%;
    top: 38.1966%;
    max-width: 855px;
    min-width: 855px;
    box-sizing: border-box;
    transform: translate(-50%, -38.1966%);
    overflow: hidden;
    padding: 20px;
    border-radius: 5px;
    " id="pico-1" role="dialog" aria-describedby="pico-1">
    <input type="hidden" id="popup_status" value="<?php echo esc_attr(get_option('respek_show_popup'));?>">
    <input type="hidden" id="popup_timestamp" value="<?php echo esc_attr(get_option('respek_timestamp_popup'));?>">
    <div id="e158_0" style="display:none;">
		<span id="e130_5">
        <?php esc_html_e('Offset Carbon.', 'respek-nature') ?><br/>
        <?php esc_html_e('Restore Nature.', 'respek-nature') ?><br/>
        <?php esc_html_e('Plant a Spekboom.', 'respek-nature') ?>
		</span>
		<span id="e130_9">
			<?php echo wp_kses(get_option('respek_popup_message'),'post');?>
		</span>
		<div id="e130_8">
			<div id="e130_6">
				<button id="contribute-button"  class="ok"><?php esc_html_e('CONTRIBUTE', 'respek-nature') ?>
          <?php echo $currency_symbol; echo $surcharge;?> 
        </button>
				<button id="no-thanks-button" class="no_thanks">
          <?php if(get_option('respek_on_us_collections') == 1) esc_html_e('THANKS!', 'respek-nature'); else esc_html_e('NO THANKS', 'respek-nature'); ?>
        </button>	
			</div>
			<a href="https://www.respeknature.org" class="learn-more" target="_blank"><?php esc_html_e('Learn more', 'respek-nature') ?></a>
		</div>
		<div id="e130_3"></div>
		<a href="https://www.respeknature.org" target="_blank">
		<div id="dark_respek_logo"></div>
		</a>
    </div>
</div>