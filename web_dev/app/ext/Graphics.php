<?php
/**
 * @author Anastasia Sidak <m0st1ce.nastya@gmail.com>
 *
 * @link https://steamcommunity.com/profiles/76561198038416053
 * @link https://github.com/M0st1ce
 *
 * @license GNU General Public License Version 3
 */

namespace app\ext;

class Graphics {

    /**
     * @since 0.2.123
     * @var object
     */
    public    $Translate;

    /**
     * @since 0.2
     * @var object
     */
    public    $General;

    /**
     * @since 0.2
     * @var object
     */
    public    $Modules;

    /**
     * @since 0.2
     * @var object
     */
    public    $Db;

    /**
     * @since 0.2
     * @var object
     */
    public    $Auth;

    /**
     * @since 0.2
     * @var object
     */
    public    $Notifications;

    /**
     * Инициализация графической составляющей вэб-интерфейса с подгрузкой модулей.
     * 
     * @param object $Translate
     * @param object $General
     * @param object $Modules
     * @param object $Db
     * @param object $Auth
     * @param object $Notifications
     *
     * @since 0.2
     */
    function __construct( $Translate, $General, $Modules, $Db, $Auth, $Notifications ) {

        // Проверка на основную константу.
        defined('IN_LR') != true && die();

        // Присвоение глобальных объектов.
        $Graphics            = $this;
        $this->Translate     = $Translate;
        $this->General       = $General;
        $this->Modules       = $Modules;
        $this->Db            = $Db;
        $this->Auth          = $Auth;
        $this->Notifications = $Notifications;

        // Подгрузка данных из модулей которые не относятся к интерфейсу и должны быть получены до начала рендера страницы.
        for ( $module_id = 0, $c_mi = sizeof( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['data'] ); $module_id < $c_mi; $module_id++ ):
            $file = MODULES . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['data'][ $module_id ] . '/forward/data.php';
            file_exists( $file ) && require $file;
        endfor;

        // Дополнительный поток под модули, которые должны задействовать ядро на постоянной основе, а не локально.
        if( ! empty( $Modules->arr_module_init['data_always'] ) ):
            for ( $module_id = 0, $c_mi = sizeof( $Modules->arr_module_init['data_always'] ); $module_id < $c_mi; $module_id++ ):
                $file = MODULES . $Modules->arr_module_init['data_always'][ $module_id ] . '/forward/data_always.php';
                file_exists( $file ) && require $file;
            endfor;
        endif;

        // Рендер блока - Head
        require PAGE . 'head.php';

        // Рендер блока - Sidebar
        require PAGE . 'sidebar.php';

        // Рендер блока - Navbar
        require PAGE . 'navbar.php';

        // Дополнительный пулл под модули, которые должны быть объявлены на каждой странице - afternavbar
        if( ! empty( $Modules->arr_module_init['interface_always']['afternavbar'] ) ):
            for ( $module_id = 0, $c_mi = sizeof( $Modules->arr_module_init['interface_always']['afternavbar'] ); $module_id < $c_mi; $module_id++ ):
                $file = MODULES . $Modules->arr_module_init['interface_always']['afternavbar'][ $module_id ]['name'] . '/forward/interface_always.php';
                file_exists( $file ) && require $file;
            endfor;
        endif;

        // Подгрузка данных из модулей которые относятся к интерфейсу - afternavbar
        if( ! empty( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface']['afternavbar'] ) ):
            for ( $module_id = 0, $c_mi = sizeof( $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface']['afternavbar'] ); $module_id < $c_mi; $module_id++ ):
                $file = MODULES . $Modules->arr_module_init['page'][ get_section( 'page', 'home' ) ]['interface']['afternavbar'][ $module_id ] . '/forward/interface.php';
                file_exists( $file ) && require $file;
            endfor;
        endif;

        // Рендер блока - Footer
        require PAGE . 'footer/1.php';
    }

    /**
     * Получение фонового ихображения.
     *
     * @since 0.2
     *
     * @return string  Ссылка на изображение ( CSS / Style )
     */
    public function get_css_background_image() {
        if ( ! empty( $this->General->arr_general['background_image'] ) && $this->General->arr_general['background_image'] != 'null' ) {
            return 'body { background-image: url(./storage/cache/img/global/backgrounds/' . $this->General->arr_general["background_image"] . ')} ';
        }
    }

    /**
     * Вывод кнопки изменения боковой панели.
     *
     * @since 0.2
     *
     * @return string Итоговый вывод.
     */
    public function get_css_sidebar_toggle() {
        if ( ! empty( $this->General->arr_general['disable_sidebar_change'] ) ) {
            return '.logo-area img {left:-8px} ';
        }
    }

    /**
     * Ограничение основного графического контейнера.
     *
     * @since 0.2
     *
     * @return string  Итоговый максимальный размер контейнера ( CSS / Style )
     */
    public function get_css_graphics_container() {
        if ( ! empty( $this->General->arr_general['graphics_container'] ) ):
            if ( $this->General->arr_general['graphics_container'] == 'static' ):
                return '.container-fluid{max-width:1400px} ';
            elseif( $this->General->arr_general['graphics_container'] == 'stretch' ):
                return '.container-fluid{max-width:1920px} ';
            endif;
        endif;
    }

    /**
     * Получение и вывод цветовой палитный сайта.
     *
     * @since 0.2
     * 
     * @return string         Цветовая плалитра ( CSS / Style / ROOT )
     */
    public function get_css_color_palette() {
        if ( isset ( $_SESSION['dark_mode'] ) && $_SESSION['dark_mode'] == true ) {
            return ':root' . str_replace( '"', '', str_replace( '",', ';', file_get_contents_fix ( 'storage/assets/css/themes/' . $this->General->arr_general['theme'] . '/palettes/' . $this->General->arr_general['dark_palette'] . '.json' ) ) ) .  ' ';
        } else {
            return ':root' . str_replace( '"', '', str_replace( '",', ';', file_get_contents_fix ( 'storage/assets/css/themes/' . $this->General->arr_general['theme'] . '/palettes/' . $this->General->arr_general['white_palette'] . '.json' ) ) ) .  ' ';
        }
    }
}