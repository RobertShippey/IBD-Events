</div>
</div>
<footer class="wrap footer">
	<div class="container footer">
		<div class="row">
			<?php if ( has_nav_menu( 'footer_menu' ) ) : ?>
				<div class="col-sm-3">
					<h4>Links</h4>
					<?php
					wp_nav_menu( array(
						'menu'              => 'footer_menu',
						'theme_location'    => 'footer_menu',
						'depth'             => 1,
						'container'         => 'ul'
						));
						?>
					</div>
				<?php endif; ?>

				<div class="col-sm-3">
					<hr class="visible-xs" />
					<h4>Contact Information</h4>
					<p>
						<strong>IBD Events</strong><br />
						Made in Norfolk, UK
					</p>

					<p>
						<a href="mailto:<?php echo antispambot('hello@ibd-events.com'); ?>">
							<?php echo antispambot('hello@ibd-events.com'); ?>
						</a>
					</p>
				</div>

				<div class="col-sm-2">
					<hr class="visible-xs" />
					<h4>Social</h4>
					<p>
						<a rel="me" target="_blank" href="https://twitter.com/IBD_Events">Twitter</a><br>
						<a rel="me" target="_blank" href="https://www.facebook.com/IBDEvents/">Facebook</a><br>
						<a rel="me" target="_blank" href="https://plus.google.com/u/0/+Ibdeventsdirectory/posts">Google Plus</a><br>
					</p>
				</div>


				<div class="col-sm-3">
					<hr class="visible-xs" />
					<h4>Newsletter</h4>
					<div id="mc_embed_signup">
						<form action="//ibd-events.us5.list-manage.com/subscribe/post?u=c9ea4c91c2&amp;id=9891de2794" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
							<div id="mc_embed_signup_scroll">

								<div class="mc-field-group form-group">
									<label for="mce-EMAIL">Email Address </label>
									<input type="email" value="" name="EMAIL" class="required email form-control input-sm" id="mce-EMAIL" >
								</div>
								<div id="mce-responses" class="clear">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
								<div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_c9ea4c91c2_9891de2794" tabindex="-1" value=""></div>
								<div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button btn btn-primary"></div>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div><!--row-->
	</footer>

	<div class="container baseline">
		<div class="row">
			<div class="col-sm-12">
				<small>
					&copy;<?php echo date("Y");?> IBD Events by <a href="http://robertshippey.net/" target="_blank">Robert Shippey</a>
				</small>
			</div>
		</div>
	</div>

	<?php get_template_part( 'foot' ); ?>