<?php
/*
 * Template Name: YIW Progetti
 */
get_header(); ?>

<?php
$wpquery = new WP_Query(array(
    'post_type'      => 'progetto',
    'orderby'        => 'meta_value',
    'meta_key'       => 'yiw_progetti_anno',
    'posts_per_page' => -1,
));

while ($wpquery->have_posts()):
    $wpquery->the_post();

    //Recupera le skill associate al progetto
    $skillsObj = get_the_terms(get_the_ID(), 'skills');
    $skills = array();
    if ($skillsObj) {
        foreach ($skillsObj as $skill) {
            $skills[] = $skill->name;
        }
    }
?>
<div class="portfolio-item">
<h2 class="portfolio-title"><?php the_title(); ?></h2>
<?php if (has_post_thumbnail()) : ?>
    <div class="portfolio-thumb"><?php the_post_thumbnail(); ?></div>
<?php endif; ?>
<div class="portfolio-content">
<?php the_content(); ?>
<?php if (!empty($skills)): ?>
<p><strong>Skills</strong>: <?php echo join(', ', $skills); ?></p>
    <?php endif; ?>
    <p><strong>Anno</strong>: <?php echo get_post_meta(get_the_ID(), 'yiw_progetti_anno', true); ?></p>
    <p><a href="<?php echo get_post_meta(get_the_ID(), 'yiw_progetti_link', true); ?>">Link al sito del progetto</a></p>
</div>
</div>
<hr>

<?php endwhile; ?>
<?php get_footer(); ?>
