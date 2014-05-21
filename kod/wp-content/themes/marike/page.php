<?php get_header()?>

	<div class="content">
			<div class="twelve columns alpha">
				<?php while(have_posts()): the_post()?>
				<h2><?php the_title()?></h2>
				<?php the_content()?>
				<?php endwhile;?> 
			</div><!-- twelve columns -->
	</div><!-- content -->
			

<?php get_footer()?>