<?php get_header()?>
	<div class="content">
	<div class="twelve columns alpha">
		<?php while(have_posts()): the_post()?>
			<div class="post">
			<h2><?php the_title()?></h2>
			<?php the_content()?>
			<div class="clear"></div></div>
		<?php endwhile;?> 
	</div>
	<?php get_sidebar()?>
	</div>
<?php get_footer()?>