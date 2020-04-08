<?php
/**
 * Modal which will pull in whatever content is in the sidebar.
 *
 * @package xten
 */

?>
<div class="modal fade" id="sidebar-modal" tabindex="-1" role="dialog" aria-labelledby="Contact Form" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
			</button>
      <?php include( get_template_directory() . '/sidebar.php'); ?>
    </div>
  </div>
</div><!-- /#sidebar-modal -->
