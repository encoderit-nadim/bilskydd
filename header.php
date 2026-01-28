<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package bilskydd-encoder-it
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<header class="bilskyddHeader bg-(--theme-dark) text-white">
		<div class="topBar bg-[rgba(255,255,255,.15)]">
			<div class="container">
				<div class="flex justify-between items-center">
					<div class="flex items-center">
						<button class="flex items-center space-x-1.5 cursor-help">
							<span>
								1–2 days delivery
							</span>
							<!-- Icon -->
							<?php include get_template_directory() . '/assets/icons/i.php'; ?>
						</button>
					</div>
				</div>
			</div>
		</div>

	</header>