<?php
// $Id: template.php,v 1.21 2009/08/12 04:25:15 johnalbin Exp $

/**
 * @file
 * Contains theme override functions and preprocess functions for the theme.
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. You can add new regions for block content, modify
 *   or override Drupal's theme functions, intercept or make additional
 *   variables available to your theme, and create custom PHP logic. For more
 *   information, please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/theme-guide
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   The Drupal theme system uses special theme functions to generate HTML
 *   output automatically. Often we wish to customize this HTML output. To do
 *   this, we have to override the theme function. You have to first find the
 *   theme function that generates the output, and then "catch" it and modify it
 *   here. The easiest way to do it is to copy the original function in its
 *   entirety and paste it here, changing the prefix from theme_ to yb2014_.
 *   For example:
 *
 *     original: theme_breadcrumb()
 *     theme override: yb2014_breadcrumb()
 *
 *   where yb2014 is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_breadcrumb() function.
 *
 *   If you would like to override any of the theme functions used in Zen core,
 *   you should first look at how Zen core implements those functions:
 *     theme_breadcrumbs()      in zen/template.php
 *     theme_menu_item_link()   in zen/template.php
 *     theme_menu_local_tasks() in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called template suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node-forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and template suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440
 *   and http://drupal.org/node/190815#template-suggestions
 */


/**
 * Implementation of HOOK_theme().
 */
function yb2014_theme(&$existing, $type, $theme, $path) {
  $hooks = zen_theme($existing, $type, $theme, $path);
  // Add your theme hooks like this:
  /*
  $hooks['hook_name_here'] = array( // Details go here );
  */
  // @TODO: Needs detailed comments. Patches welcome!
  
  $hooks['siteview_change_request_node_form'] = array(
    'arguments' => array('form' => NULL),
  );

  return $hooks;
}

/**
 * Generate the HTML output for a menu item and submenu.
 *
 * @ingroup themeable
 */
function yb2014_menu_item($link, $has_children, $menu = '', $in_active_trail = FALSE, $extra_class = NULL) {
  $class = ($menu ? 'expanded' : ($has_children ? 'collapsed' : 'leaf'));
  if (!empty($extra_class)) {
    $class .= ' '. $extra_class;
  }
  if ($in_active_trail) {
    $class .= ' active-trail';
  }

  $output = '';
  $output .= '<li class="'. $class .'">';

  // Add custom elements for theming.
  $output .= '<span class="li-left"></span><span class="li-middle">';

  $output .= $link . $menu;

  // Add custom elements for theming.
  $output .= '<span class="li-middle-background"></span></span><span class="li-right"></span>';

  $output .= "</li>\n";
  return $output;

}

/**
 * Override or insert variables into all templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered (name of the .tpl.php file.)
 */
/* -- Delete this line if you want to use this function
function yb2014_preprocess(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
function yb2014_preprocess_page(&$vars, $hook) {
  if ($vars['tabs']) {
    $vars['classes_array'][] = 'with-tabs';
  }
  if (!user_is_logged_in()) {
    //drupal_add_js('https://myyouthbuild.wapixstaging.com/sites/default/files/civicrm/ext/com.wannapixel.ybstyles/js/formstylesfront_2.js');
    //drupal_add_css('https://myyouthbuild.wapixstaging.com/sites/default/files/civicrm/ext/com.wannapixel.ybstyles/css/formstylesfront.css');
    drupal_add_css(
        path_to_theme() .'/css/formstylesfront.css'
    );
    drupal_add_js(path_to_theme() . '/js/formstylesfront_2.js');
  }
}

function yb2014_siteview_change_request_node_form($form) {
  //Put the comments last
  $body = drupal_render($form['body_field']);
  $buttons = drupal_render($form['buttons']);

  return drupal_render($form) . $body . $buttons;
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
function yb2014_preprocess_node(&$vars, $hook) {
  
  //Inject the first field_image into the node teaser
  if($vars['teaser'] && $vars['field_image'][0]['filepath']) {
    $vars['teaser_image'] = theme("imagecache", "Thumbnail_100x100", $vars['field_image'][0]['filepath']);
    //extract the image height, so it can be centered vertically
    $matches = array();
    preg_match("/(height=\")([0-9]*)(\")/", $vars['teaser_image'], $matches);
    $height = $matches[2];
    $vars['teaser_image_margin'] = floor(($height / 2));
    $vars['classes_array'][] = 'with-image';
  }

  //create a list of links for the tags vocabulary (vid = 2)
  if($vars['taxonomy']) {
    $tags = taxonomy_node_get_terms_by_vocabulary($vars['node'], 2);
    $links = array();
    foreach($tags as $tag) {
      $links['taxonomy_term_' . $tag->tid] = array(
        'title' => $tag->name,
        'href' => taxonomy_term_path($tag),
        'atttributes' => array(
          'rel' => 'tag',
          'title' => strip_tags($tag->description),
        ),
      );
    }
    $vars['tags'] = theme_links($links);
  }

  // Optionally, run node-type-specific preprocess functions, like
  // yb2014_preprocess_node_page() or yb2014_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $vars['node']->type;
  if (function_exists($function)) {
    $function($vars, $hook);
  }
}

function _yb2014_ordinalize_district($district) {
  $d = intval($district);
  if (in_array($d % 100, range(11,13))) {
    $suffix = "th";
  }
  else {
    switch($d % 10) {
    case 1:
      $suffix = "st";
      break;
    case 2:
      $suffix = "nd";
      break;
    case 3:
      $suffix = "rd";
      break;
    default:
      $suffix = "th";
      break;
    }
  }
  return "$d<sup>$suffix</sup>";
}

function _yb2014_civicrm_state($abbr) {
  static $states = array();
  if (!isset($states[$abbr])) {
    $query = db_fetch_array(db_query("SELECT id FROM {civicrm_state_province} WHERE Abbreviation = '%s'", $abbr));
    $states[$abbr] = $query['id'];
  }
  return $states[$abbr];
}

function yb2014_legislator_glows($nid) {
  static $glows = "";
  if ($glows === "") {
    $glows == array();
    $nodes = db_query("SELECT ll.nid, ll.field_single_description_value, field_legislators_nid FROM {content_type_legislator_list} ll INNER JOIN {content_field_legislators} l ON ll.nid = l.nid WHERE field_glow_value = 'yes'");
    while ($row = db_fetch_array($nodes)) {
      $glows[$row['field_legislators_nid']][] = l($row['field_single_description_value'], "node/" . $row['nid']);
    }
  }

  return $glows[$nid] ? $glows[$nid] : array();
}

function yb2014_preprocess_node_legislator(&$vars, $hook) {
  $leg_title = $vars['field_leg_title'][0]['value'];

  $vars['glow'] = yb2014_legislator_glows($vars['nid']);

  if ($vars['page']) {
    $lists = views_get_view('legislator_lists');
    $vars['lists'] = $lists->preview('default', array($vars['nid']));
    if (count($lists->result) == 0) {
      unset($vars['lists']);
    }
  }

  if( $leg_title == 'Rep') {
    $district_num = $vars['field_district'][0]['value'];
    $district = $vars['field_state'][0]['value'] . '-' . $vars['field_district'][0]['value'];
    $district_ordinalized = _yb2014_ordinalize_district($vars['field_district'][0]['value']);

    $view = views_get_view('sites_for_district');
    $view->preview('default', array($district));

    if (!$vars['teaser']) {
      $vars['sites'] = $view->display_handler->output;
    }

    $p = array(
      '@state' => $vars['field_state'][0]['view'],
      '!district' => $district_ordinalized,
      '@st' => $vars['field_state'][0]['safe'],
      '@party' => $vars['field_party'][0]['view'],
      '@isare' => count($view->result) == 1 ? "is" : "are",
      '@count' => count($view->result) == 0 && $vars['Page'] ? "no programs" : format_plural(count($view->result), "1 program", "@count programs"),
    );

    if ($district_num == "00") {
      $vars['sites_header'] = t($vars['page'] ? "There @isare @count in @state" : "@count in state" , $p);
      $vars['leg_description'] = t($vars['page'] ? "Representative at-large for @state" : "@party, At-Large", $p);
    }
    else {
      $vars['leg_description'] = t($vars['page'] ? "Representative for @state's !district district" : "@party, @st !district", $p);
      $vars['sites_header'] = t($vars['page'] ? "There @isare @count in @state's !district district" : "@count in district", $p);
    }
  }
  else {
    $civi_state = _yb2014_civicrm_state($vars['field_state'][0]['value']);
    $view = views_get_view('sites_for_state');
    $view->preview('default', array($civi_state));

    $vars['sites_header'] = t($vars['page'] ? "There @isare @count in @state" : "@count in @state", array(
      '@isare' => count($view->result) == 1 ? 'is' : 'are',
      '@count' => count($view->result) == 0 && $vars['page'] ? "no programs" : format_plural(count($view->result), "1 program", "@count programs"),
      '@state' => $vars['field_state'][0]['view'],
    ));
    if (!$vars['teaser']) {
      $vars['sites'] = $view->display_handler->output;
    }

    if ($leg_title == 'Sen') {
      $seat = $vars['field_senate_seat'][0]['value'] == 'Senior Seat' ? 'senior' : 'junior';
      $vars['leg_description'] = t($vars['page'] ? "@state's @seat senator" : "@party, @seat", array(
        "@party" => $vars['field_party'][0]['view'],
        "@state" => $vars['field_state'][0]['view'],
        '@seat' => $vars['page'] ? $seat : $vars['field_senate_seat'][0]['view'],
      ));
    }
    else {
      $vars['leg_description'] = t("@title for @state", array("@title" => $vars['field_leg_title'][0]['view'], '@state' => $vars['field_state'][0]['view']));
    }
  }


  $tp = array('@name' => $vars['title']);
  if ($vars['field_links'][0]['url']) {
    $vars['official_website'] = l(t($vars['page'] ? "@name's Website" : "Website", $tp), $vars['field_links'][0]['url']);
  }
  if ($vars['field_links'][1]['url']) {
    $vars['contact_form'] = l(t($vars['page'] ? "Contact @name" : "Contact", $tp), $vars['field_links'][1]['url']);
  }
  if ($vars['field_links'][2]['url']) {
    $vars['facebook'] = l(t("@name on FaceBook", $tp), $vars['field_links'][2]['url']);
  }
  if ($vars['field_links'][3]['url']) {
    $vars['twitter'] = l(t("@name on Twitter", $tp), $vars['field_links'][3]['url']);
  }
  if ($vars['field_links'][4]['url']) {
    $vars['youtube'] = l(t("@name on YouTube", $tp), $vars['field_links'][4]['url']);
  }
}

function yb2014_preprocess_node_legislator_list(&$vars) {
  $vars['plural'] = format_plural(count($vars['field_legislators']), "1 Legislator", "@count Legislators");
}

function yb2014_preprocess_node_kb_item(&$vars, $hook) {
  $authors = array_map(function($item) { return $item['safe']; }, $vars['field_kb_author'] );
  $vars['kb_authors'] = implode(", ", array_slice($authors, 0, -1)) . (count($authors) > 1 ? " and " . $authors[count($authors) - 1] : $authors[0]);
  $vars['kb_copyright'] = $vars['field_kb_copyright'][count($vars['field_kb_copyright'])-1]['safe'];
}

function yb2014_preprocess_node_funding_op(&$vars, $hook) {
}

/**
 * Override or insert variables into the comment templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function yb2014_preprocess_comment(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $vars
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function yb2014_preprocess_block(&$vars, $hook) {
  $vars['sample_variable'] = t('Lorem ipsum.');
}
// */


function yb2014_username($object) {
  if($object->uid && $object->name) {
    $username = _ybusa_get_civicrm_name($object->uid, $object->name); 
    if(user_access('access user profiles')) {
      $output = l($username, 'user/' . $object->uid, array(
        'attributes' => array(
          'title' => t('View user profile'),
        ),
      ));
    } else {
      $output = check_plain($username);
    }
  } else if ($object->name) {
    if(! empty($object->homepage)) {
      $output = l($object->name, $object->homepage, array(
        'attributes' => array(
          'rel' => 'nofollow',
        ),
      ));
    } else {
      $output = check_plain($object->name);
    }
    $output .= ' (' . t('not verified') . ')';
  } else {
    $output = check_plain(variable_get('anonymous', t('Anonymous')));
  }
  return $output;
}

function _ybusa_get_civicrm_name($uid, $name) {
  static $users = array();
  if(! isset($users[$uid]) ) {
    $contact = db_fetch_array(db_query("SELECT first_name, last_name, organization_name FROM {civicrm_contact} c INNER JOIN {civicrm_uf_match} m on c.id = m.contact_id WHERE m.uf_id = %d", $uid));
    if($contact['first_name'] || $contact['last_name']) {
      $users[$uid] = trim($contact['first_name'] . " " . $contact['last_name']);
    } else if ($contact['organization_name']) {
      $users[$uid] = $contact['orgaization_name'];
    } else {
      $users[$uid] = $name;
    }
  }
  return $users[$uid];
}

function ybusamodule_preprocess_content_field(&$vars) {
  if($vars['field_name'] == 'field_email_image') {
    ybusamodule_preprocess_content_field_email_image($vars);  
  }
  
  if($vars['field_name'] == 'field_funding_ybusa_contact') {
    ybusamodule_preprocess_content_funding_ybusa_contact($vars);
  }

}

function yb2014_preprocess_content_field(&$vars) {
  if ($vars['field_name'] == 'field_profile_contact') {
    if (!user_access('access CiviCRM')) {
      $vars['page'] = FALSE;
      $vars['items'] = array();
    }
  }
}

function ybusamodule_preprocess_content_funding_ybusa_contact(&$vars) {
  foreach($vars['items'] as $delta => &$item) {
    $contact = civicrm_api("Contact", "get", array('version' => 3,
      'id' => $item['contact_id'],
      'return.display_name' => 1,
      'return.email' => 1,
      'return.phone' => 1,
    ));
    $item['contact'] = $contact['values'][$item['contact_id']];
  }
}

function ybusamodule_preprocess_content_field_email_image(&$vars) {
  $settings = variable_get('yb2014_email_imagecache_presets', "email_small|Small Email Image;email_banner|Email Banner Image");
  foreach(explode(";", $settings) as $setting) {
    $parts = explode("|", $setting);
    $imagecache_presets[$parts[0]] = $parts[1];
  }

  global $base_url;
  foreach($vars['items'] as $delta => $item) {
    $output = "<dl>";
    $output .= "<dt>Original Image</dt>"; 
    $output .= "<dd>" . l($base_url . '/' . $item['filepath'], $item['filepath']) . "</dd>";
    foreach($imagecache_presets as $preset => $preset_label) {
      $path = imagecache_create_path($preset, $item['filepath']);
      $output .= "<dt>" . $preset_label . "</dt>"; 
      $output .= "<dd>" . l($base_url . '/' . $path, $path) . "</dd>";
    }
    $output .= "</dl>";
    $vars['items'][$delta]['imagecache_urls'] = $output;
  }
}

function yb2014_preprocess_views_view_field__myyb_coaches__display_name_1(&$vars) {
  $sites = explode('|', $vars['row']->civicrm_contact_civicrm_relationship_display_name);
  foreach ($sites as $site) {
    list($name, $id) = explode(':', $site);
    $vars['sites'][$id] = $name;
  }
}

function yb2014_preprocess_views_view_table__myyb_watchdog_denied(&$vars) {
  $to_remove = array();
  foreach ($vars['rows'] as $i => &$row) {
    if (preg_match('/node\/([0-9]+)/', $row['message'], $match)) {
      $node = node_load($match[1]);
      $row['message'] = l($node->title, $node->path);

      if($node->field_myyb_section[0]['nid']) {
        $config = node_load($node->field_myyb_section[0]['nid']);
        $row['nothing'] = $config->title;
        if (!node_access('view', $config)) {
          array_unshift($to_remove, $i);
        }
      }

      $terms = array();
      foreach ($node->taxonomy as $term) {
        if ($term->vid == 7) {
          $terms[] = $term->name;
        }
      }
      if (count($terms) > 0) {
        $row['nothing_1'] = (count($terms) == 1 ? $terms[0] : implode(", ", array_slice($terms, 0, -1)) . " or " . end($terms));
      }
    }
    else {
      array_unshift($to_remove, $i);
    }
  }

  foreach ($to_remove as $i) {
    unset($vars['rows'][$i]);
  }
}

function yb2014_preprocess_node_profile(&$vars) {
  // Set the profile image to the first image
  $vars['profile_image'] = array_shift($vars['field_image']);
}

/**
 * Implements template_preprocess_html().
 */
function yb2014_preprocess_html(&$variables) {
  drupal_add_js('//use.typekit.net/hab5szb.js', 'external');
  drupal_add_js('try{Typekit.load();}catch(e){}', 'inline', 'page_bottom');
}
