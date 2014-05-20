<?php
/*
Template Name: Ramundberget
*/
?>
<?php get_header()?>

	<div id="content">
			<div class="twelve columns alpha">
				<?php while(have_posts()): the_post()?>
				<h2>Ramundberget</h2>
				<?php the_content()?>
				<?php endwhile;?>
			</div><!-- twelve columns -->
	<?php get_sidebar()?>
	</div><!-- content -->
			

<?php get_footer()?>
