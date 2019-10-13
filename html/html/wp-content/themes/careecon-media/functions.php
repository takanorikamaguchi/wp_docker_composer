<?php
add_theme_support('menus');

register_nav_menus( array(
'headernav' => 'ヘッダー',
'sidebarnav' => 'サイドバー',
'footernav' => 'フッター',
));

//add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support( 'automatic-feed-links' );

//function wpbeg_title( $title ){
//    if ( is_front_page() && is_home() ) {
//        $title = wp_title( '|', true, 'right' );
//    } elseif( is_singular() ) {
//        $title = single_post_title('', false);
//    }
//     return $title;
//}
//add_filter('pre_get_document_title','wpbeg_title' );
if ( !is_admin() ) {
function hc_script(){
    wp_deregister_script('jquery');
    // wp_enqueue_style( 'normalize', get_template_directory_uri() . '/css/normalize.css' , array() );
    wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css' , array() , '1.0.0' );
}
add_action('wp_enqueue_scripts','hc_script' );

function wpfj_script(){
    wp_enqueue_script( 'jquery-3.2.1', get_template_directory_uri() . 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js' , array() ,'', true );
    // wp_enqueue_script( 'main', get_template_directory_uri() . '/js/main.js' , array() ,'', true);
    // if(is_page(array(75))){
    //  wp_enqueue_script( 'contact', get_template_directory_uri() . '/js/contact.js' , array() ,'', true );
    // }
    // if(is_page(array(77))){
    //  wp_enqueue_script( 'next', get_template_directory_uri() . '/js/next.js' , array() ,'', true );
    // }
}
add_action('wp_enqueue_scripts','wpfj_script' );
}

function theme_slug_widgets_init() {
	register_sidebar(array(
		'name' => 'sidebar-1',
		'description' => 'サイドバー',
		'id' => 'sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="sidebar--ttl">',
        'after_title'   => '</h3>',
        'items_wrap' => '<li class="sidebar--item">%3$s</li>'
	));

		register_sidebar(array(
		'name' => 'footer1',
		'description' => 'footer1',
        'id' => 'footer1',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
	));
	register_sidebar( array(
        'name'          => 'footer2',
        'description' => 'footer2',
        'id' => 'footer2',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3>',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'theme_slug_widgets_init' );

add_action( 'init', 'create_post_type' );
    function create_post_type() {
      register_post_type( 'notice',
        array(
          'labels' => array(
            'name' => __( 'お知らせ' ),
            'singular_name' => __( 'notice' )
          ),
          'public' => true,
          'menu_position' => 5,
          'supports' => array('title','editor','thumbnail',
          'custom-fields','excerpt','author','trackbacks',
          'comments','revisions','page-attributes')
        )
      );

      register_taxonomy(
        'noticecat',
        'notice',
        array(
          'hierarchical' => true,
          'update_count_callback' => '_update_post_term_count',
          'label' => 'blogのカテゴリー',
          'singular_label' => 'お知らせのカテゴリー',
          'public' => true,
          'show_ui' => true
        )
      );

     }

add_filter('previous_post_link','add_prev_post_link_class');
function add_prev_post_link_class($output){
  return str_replace('<a href=','<a class="nav-post--item-link" href=', $output);
}
add_filter('next_post_link','add_next_post_link_class');
function add_next_post_link_class($output){
  return str_replace('<a href=','<a class="nav-post--item-link" href=', $output);
}

if ( !current_user_can('administrator') ) {
add_action('admin_menu','remove_admin_menu', 999);
  function remove_admin_menu(){
  remove_menu_page( 'edit.php?post_type=page' );
  remove_menu_page( 'edit-comments.php' );
}
}
add_action('admin_menu','remove_admin_submenu', 999);
  function remove_admin_submenu(){
  remove_submenu_page( 'themes.php', 'theme-editor.php' );
  remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
  remove_submenu_page( 'options-general.php', 'options-permalink.php' );
}

function time_session(){
 if( is_home() ) setcookie('applink', 'true', time() + 60*60*24, '/' );
 setcookie('isMoble', wp_is_mobile() ? 'true' : 'false', 0 , '/');
}

add_action( 'get_header', 'time_session' );

function breadcrumb(){
    global $post;
      $str ='';
      if(!is_home()&&!is_admin()){
          $str.= '<div id="breadcrumb" class="breadcrumb clearfix">';
          $str.= '<ul class="breadcrumb--list">';
          $str.= '<li class="breadcrumb--item"><a href="'. home_url() .'/">HOME</a></li>';
          $str.= '<li class="breadcrumb--item">&gt;</li>';
          if(is_search()){
              $str.='<li class="breadcrumb--item">「'. get_search_query() .'」で検索した結果</li>';
          } elseif(is_tag()){
              $str.='<li class="breadcrumb--item">タグ : '. single_tag_title( '' , false ). '</li>';
          } elseif(is_404()){
              $str.='<li class="breadcrumb--item">404 Not found</li>';
          } elseif(is_date()){
              if(get_query_var('day') != 0){
                  $str.='<li class="breadcrumb--item"><a href="'. get_year_link(get_query_var('year')). '">' . get_query_var('year'). '年</a></li>';
                  $str.='<li class="breadcrumb--item">&gt;</li>';
                  $str.='<li class="breadcrumb--item"><a href="'. get_month_link(get_query_var('year'), get_query_var('monthnum')). '">'. get_query_var('monthnum') .'月</a></li>';
                  $str.='<li class="breadcrumb--item">&gt;</li>';
                  $str.='<li class="breadcrumb--item">'. get_query_var('day'). '日</li>';
              } elseif(get_query_var('monthnum') != 0){
                  $str.='<li class="breadcrumb--item"><a href="'. get_year_link(get_query_var('year')) .'">'. get_query_var('year') .'年</a></li>';
                  $str.='<li class="breadcrumb--item">&gt;</li>';
                  $str.='<li class="breadcrumb--item">'. get_query_var('monthnum'). '月</li>';
              } else {
                  $str.='<li class="breadcrumb--item">'. get_query_var('year') .'年</li>';
              }
          } elseif(is_category()){
              $cat = get_queried_object();
              if($cat -> parent!= 0){
                  $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category' ));
                  foreach ($ancestors as $ancestor) {
                     $str.='<li class="breadcrumb--item"><a href="'. get_category_link($ancestor) .'">'. get_cat_name($ancestor) .'</a></li>';
                     $str.='<li class="breadcrumb--item">&gt;</li>';
                  }
              }
                  $str.='<li class="breadcrumb--item">'. $cat -> name .'</li>';
          } elseif(is_page()){
                  if ($post -> post_parent != 0) {
                      $ancestors = array_reverse(get_post_ancestors( $post->ID));
                                     foreach ($ancestors as $ancestor) {
                                        $str.='<li class="breadcrumb--item"><a href="'. get_permalink($ancestor) .'">'. get_get_title($ancestor) .'</a></li>';
                                        $str.='<li class="breadcrumb--item">&gt;</li>';
                                     }
                  }
                    $str.='<li class="breadcrumb--item">'. $post -> post_title .'</li>';
          }elseif(is_attachment()){
                  if ($post -> post_parent != 0) {
                  $str.='<li class="breadcrumb--item"><a href="'. get_permalink($post -> post_parent). '">'. get_the_title($ancestor) .'</a></li>';
                  $str.='<li class="breadcrumb--item">&gt;</li>';
                  }
               $str.='<li class="breadcrumb--item">'. $post -> post_title .'</li>';
          }elseif(is_single()){
              $categories = get_the_category($post->ID);
              $cat = $categories[0];
              if($cat -> parent != 0 ){
                  $ancestors = array_reverse(get_ancestors( $cat -> cat_ID, 'category'));
                  foreach ($ancestors as $ancestor) {
                     $str.='<li class="breadcrumb--item"><a href="'. get_category_link($ancestor) .'">'. get_cat_name($ancestor) .'</a></li>';
                     $str.='<li class="breadcrumb--item">&gt;</li>';
                  }
              }
                     $str.='<li class="breadcrumb--item"><a href="'. get_category_link($cat -> term_id) .'">'. $cat -> cat_name .'</a></li>';
                     $str.='<li class="breadcrumb--item">&gt;</li>';
                     $str.='<li class="breadcrumb--item">'. $post -> post_title .'</li>';
          }
          else{
          $str.='<li class="breadcrumb--item">'. wp_title('', false) .'</li>';
          }
          $str.='</ul>';
          $str.='</div>';
      }
      echo $str;
}
