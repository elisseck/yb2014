<?php
// $Id: node.tpl.php,v 1.10 2009/11/02 17:42:27 johnalbin Exp $

/**
 * @file
 * Theme implementation to display a node.
 *
 * Available variables:
 * - $title: the (sanitized) title of the node.
 * - $content: Node body or teaser depending on $teaser flag.
 * - $user_picture: The node author's picture from user-picture.tpl.php.
 * - $date: Formatted creation date. Preprocess functions can reformat it by
 *   calling format_date() with the desired parameters on the $created variable.
 * - $name: Themed username of node author output from theme_username().
 * - $node_url: Direct url of the current node.
 * - $terms: the themed list of taxonomy term links output from theme_links().
 * - $display_submitted: whether submission information should be displayed.
 * - $links: Themed links like "Read more", "Add new comment", etc. output
 *   from theme_links().
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It can be manipulated through the variable $classes_array from
 *   preprocess functions. The default values can be one or more of the
 *   following:
 *   - node: The current template type, i.e., "theming hook".
 *   - node-[type]: The current node type. For example, if the node is a
 *     "Blog entry" it would result in "node-blog". Note that the machine
 *     name will often be in a short form of the human readable label.
 *   - node-teaser: Nodes in teaser form.
 *   - node-preview: Nodes in preview mode.
 *   The following are controlled through the node publishing options.
 *   - node-promoted: Nodes promoted to the front page.
 *   - node-sticky: Nodes ordered above other non-sticky nodes in teaser
 *     listings.
 *   - node-unpublished: Unpublished nodes visible only to administrators.
 *   The following applies only to viewers who are registered users:
 *   - node-by-viewer: Node is authored by the user currently viewing the page.
 *
 * Other variables:
 * - $node: Full node object. Contains data that may not be safe.
 * - $type: Node type, i.e. story, page, blog, etc.
 * - $comment_count: Number of comments attached to the node.
 * - $uid: User ID of the node author.
 * - $created: Time the node was published formatted in Unix timestamp.
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 * - $zebra: Outputs either "even" or "odd". Useful for zebra striping in
 *   teaser listings.
 * - $id: Position of the node. Increments each time it's output.
 *
 * Node status variables:
 * - $build_mode: Build mode, e.g. 'full', 'teaser'...
 * - $teaser: Flag for the teaser state (shortcut for $build_mode == 'teaser').
 * - $page: Flag for the full page state.
 * - $promote: Flag for front page promotion state.
 * - $sticky: Flags for sticky post setting.
 * - $status: Flag for published status.
 * - $comment: State of comment settings for the node.
 * - $readmore: Flags true if the teaser content of the node cannot hold the
 *   main body content.
 * - $is_front: Flags true when presented in the front page.
 * - $logged_in: Flags true when the current user is a logged-in member.
 * - $is_admin: Flags true when the current user is an administrator.
 *
 * The following variables are deprecated and will be removed in Drupal 7:
 * - $picture: This variable has been renamed $user_picture in Drupal 7.
 * - $submitted: Themed submission information output from
 *   theme_node_submitted().
 *
 * @see template_preprocess()
 * @see template_preprocess_node()
 * @see zen_preprocess()
 * @see zen_preprocess_node()
 * @see zen_process()
 */
?>
<div id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix">


  <?php if ($unpublished): ?>
    <div class="unpublished"><?php print t('Unpublished'); ?></div>
  <?php endif; ?>


  <div class="content clearfix">
    <?php if($field_inoffice[0]['value'] == 'no'): ?>
      <div class="out-of-office"><?php print $title ?> is no longer in office.</div>
    <?php endif; ?>
    <div class="legislator-picture <?= (count($glow) > 0 ? 'glow' : '') ?>">
      <?php if ($field_legislator_picture_rendered): ?>
        <?php print $field_legislator_picture_rendered ?>
      <?php else: ?>
        <div class="field field-type-filefield field-field-legislator-picture">
          <div class="field-items">
            <div class="field-item odd">
              <?php if($teaser): ?>
                <img class="imagefield imagefield-field_legislator_picture" width="75" height="91" alt="" src="http://placehold.it/75x91/ffffff&text=No+Picture" >
              <?php else: ?>
                <img class="imagefield imagefield-field_legislator_picture" width="200" height="250" alt="" src="http://placehold.it/200x250/ffffff&text=No+Picture" >
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if (count($glow) > 0 && !$page): ?>
        <div class='glow-info'>
          <ul>
            <?php foreach($glow as $glow_item): ?>
              <li><?php print $glow_item ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif;?>
    </div>

    <?php if ($page): ?>
      <h1><?php print $title ?></h1>
    <?php else: ?>
      <h2 class="title"><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <div class='info'>
      <?php if ($page): ?>
        <div class='party party-<? print $field_party[0]['safe'] ?>'>
          <?php print $field_party[0]['view'] ?>
        </div>
      <?php endif; ?>
      <div class='description'><?php print $leg_description ?></div>
      <?php if ($official_website || $contact_form): ?>
        <div class='official'>
          <?php if ($official_website): ?>
            <div class='website'><?php print $official_website ?></div>
          <?php endif; ?>
          <?php if ($contact_form): ?>
            <div class='contact-form'><?php print $contact_form ?></div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if($page): ?>
        <?php if ( $facebook || $twitter || $youtube ): ?>
          <div class='social-media'>
            <?php if ($facebook): ?>
              <span class='facebook'>
                <?php print $facebook; ?>
              </span>
            <?php endif;?>
            <?php if ($twitter): ?>
              <span class='twitter'>
                <?php print $twitter; ?>
              </span>
            <?php endif;?>
            <?php if ($youtube): ?>
              <span class='youtube'>
              <?php print $youtube; ?>
              </span>
            <?php endif;?>
          </div>
        <?php endif; ?>

        <div class='other'>
          <span>More information about <?php print $title ?>:</span>
          <ul>
            <?php for($i = 5; $i < count($field_links); $i++): ?>
              <li><?php print $field_links[$i]['view']; ?></li>
            <?php endfor; ?>
          </ul>
        </div>
      <?php endif; ?>
        

      <?php if($teaser && $sites_header): ?>
        <div class='sites'>
          <?php print $sites_header ?>
        </div>
      <?php endif; ?>

    </div>

  </div>

  <?php if($page): ?>
    <?php if($lists): ?>
      <h2><?php print $title ?>:</h2>
      <?php print $lists ?>
    <?php endif; ?>
  <?php endif; ?>

  <?php if($page && $sites_header): ?>
    <h3><?= $sites_header ?></h3>
    <?= $sites ?>
  <?php endif; ?>

  <?php if($tags): ?>
    <div class='tags'><?php print $tags ?></div>
  <?php endif; ?>

  <?php print $links; ?>
</div> <!-- /.node -->
