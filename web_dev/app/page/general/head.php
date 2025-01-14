<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo $Modules->get_page_description()?>">
    <meta property="og:description" content="<?php echo $Modules->get_page_description()?>">
    <meta name="author" content="https//vk.com/M0st1ce">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <title><?php echo $Modules->get_page_title()?></title>
    <meta property="og:title" content="<?php echo $Modules->get_page_title()?>">
    <meta property="og:image" content="<?php echo $Modules->get_page_image()?>">
    <link rel="image_src" href="<?php echo $Modules->get_page_image()?>">
    <meta name="twitter:image" content="<?php echo $Modules->get_page_image()?>">
<?php if( empty( $General->arr_general['enable_css_cache'] ) ) :
        for ( $style = 0, $style_s = sizeof( $Modules->css_library ); $style < $style_s; $style++ ):?>
    <link rel="stylesheet" type="text/css" href="<?php echo $General->arr_general['site'] . $Modules->css_library[ $style ]?>">
<?php   endfor;
if( ! empty( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['css'] ) ):
    for ( $css = 0, $css_s = sizeof( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['css'] ); $css < $css_s; $css++ ):?>
    <link rel="stylesheet" type="text/css" href="<?php echo $General->arr_general['site'] . 'app/modules/' . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['css'][ $css ]['name'] . '/assets/css/' . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['css'][ $css ]['type'] . '.css'?>">
  <?endfor;
endif;
      else:?>
    <link rel="stylesheet" type="text/css" href="<?php echo ! file_exists( ASSETS_CSS . '/generation/style_generated.min.ver.' . $Modules->actual_library['actual_css_ver'] . '.css' ) ? $General->arr_general['site'] . 'storage/assets/css/themes/' . $General->arr_general['theme'] . '/style' :  $General->arr_general['site'] . 'storage/assets/css/generation/style_generated.min.ver.' . $Modules->actual_library['actual_css_ver']?>.css">
<?php endif; ?>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <style>
<?php echo $Graphics->get_css_color_palette()?>
<?php echo $Graphics->get_css_background_image()?>
<?php echo $Graphics->get_css_graphics_container()?>
<?php echo $Graphics->get_css_sidebar_toggle()?>
.sidebar-collapse .user-sidebar-block { <?php echo ! isset( $_SESSION['steamid32'] ) ? 'height: 59px;' : 'height: 88px;' . '} '?></style>
    <script>var avatar=[];var servers=[];var profile;</script>
</head>
<?php
// Дополнительный пулл под модули, которые должны быть объявлены на каждой странице - afterhead
if( ! empty( $Modules->arr_module_init['interface_always']['afterhead'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->arr_module_init['interface_always']['afterhead'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->arr_module_init['interface_always']['afterhead'][ $module_id ]['name'] . '/forward/interface_always.php';
        file_exists( $file ) && require $file;
    endfor;
endif;

// Подгрузка данных из модулей которые относятся к интерфейсу - afterhead
if( ! empty( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface']['afterhead'] ) ):
    for ( $module_id = 0, $c_mi = sizeof( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface']['afterhead'] ); $module_id < $c_mi; $module_id++ ):
        $file = MODULES . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface']['afterhead'][ $module_id ] . '/forward/interface.php';
        file_exists( $file ) && require $file;
    endfor;
endif?>
<body <?php $_SESSION['sidebar_open'] == false && print 'class="sidebar-collapse"'?>>