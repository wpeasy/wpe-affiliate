<?php

?>
<!-- Note wpe-admin namespace -->
<div class="wrap wpe-admin">
    <h1>WPEasy Affiliate Tools</h1>
    <div id="wpe-feedback-container" style="display: none">
    </div>
    <form method="post" action="options.php">
		<?php
		settings_fields( $option_group );
		do_settings_sections( $menu_slug );
		?>

		<?php submit_button(); ?>
    </form>

    <!-- Template for VanillaModal from npm -->
    <div class="modal" id="affiliateModal">
        <div class="modal-inner">
            <span data-modal-close>&times;</span>
            <div class="modal-content"></div>
        </div>
    </div>

    <!-- Template for Modal content -->
    <div id="scModal" style="display:none">
        <div class="modalHeading">Link Generator</div>
        <div class="modalContent">
            <p>To use an HTML Link, please click the box below to copy it to the clipboard.</p>
            <div id="generatedLink">
                <div id="generatedLinkText"></div>
                <input type="text" id="generatedLinkInput" value="NO Ad Selected">
            </div>
            <p>To use as a WordPress ShortCode, please click the box below to copy it to the clipboard.</p>
            <div id="generatedShortcode">
                <div id="generatedShortcodeText"></div>
                <input type="text" id="generatedShortcodeInput" value="NO Ad Selected">
            </div>
        </div>
    </div>


    <hr/>
    <div id="wpe-affiliate-sc-container" style="display: none">
        <div class="wpe-header">
            <h2>Affiliate Link Generator <small>Click on advert below to get your link</small></h2>
        </div>
        <div id="#wpe-aff-generated-sc"></div>

        <div class="wpe-affiliate-ad-list">
			<?php
			$srcRoot     = $banners['srcRoot'];
			$images      = $banners['images'];
			$imageCount  = count( $images );
			$itemsPerRow = 2;
			$imageIndex  = 0;
			$rowCount    = ceil( $imageCount / $itemsPerRow );

			for ( $rowIndex = 0; $rowIndex < $rowCount; $rowIndex ++ ) {

				echo '<div class="row">';
				for ( $colIndex = 0; $colIndex < $itemsPerRow; $colIndex ++ ) {
					$image = $images[ $imageIndex ];
					$imageIndex ++;
					echo '<div class="col">';
					?>
                    <div class="item mb-3">
                        <img class="item" name="<?= $image['name'] ?>" width="<?= $image['width'] ?>"
                             height="<?= $image['height'] ?>" src="<?= $srcRoot . $image['name'] ?>"/>
                    </div>
					<?php
					echo '</div>';
				}
				echo '</div>';
			}
			?>
        </div>
    </div>
</div>
