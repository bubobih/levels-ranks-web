<?php
/**
 * @author Anastasia Sidak <m0st1ce.nastya@gmail.com>
 *
 * @link https://steamcommunity.com/profiles/76561198038416053
 * @link https://github.com/M0st1ce
 *
 * @license GNU General Public License Version 3
 */

// Задаём основную кодировку страницы.
header('Content-Type: text/html; charset=utf-8');

// Отключаем вывод ошибок.
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Ограничиваем время выполнения скрипта.
set_time_limit(4);

// Нахожение в пространстве LR.
define('IN_LR', true);

// Версия LR WEB.
define('VERSION', '0.2.124');

// Основная директория вэб-приложения.
define('APP', 'app/');

// Директория содержащая основные блоки вэб-приложения.
define('PAGE', APP . 'page/general/');

// Директория содержащая дополнительные блоки вэб-приложения.
define('PAGE_CUSTOM', APP . 'page/custom/');

// Директория с модулями.
define('MODULES', APP . 'modules/');

// Директория с основными конфигурационными файлами.
define('INCLUDES', APP . 'includes/');

// Директория содержащая графические кэш-файлы.
define('CACHE', 'storage/cache/');

// Директория с ресурсами.
define('ASSETS', 'storage/assets/');

// Директория с основными кэш-файлами.
define('SESSIONS', CACHE . 'sessions/');

// Директория содержащая логи.
define('LOGS', CACHE . 'logs/');

// Директория содержащая изображения.
define('IMG', CACHE . 'img/');

// Директория с CSS шаблонами.
define('ASSETS_CSS', ASSETS . 'css/');

// Директория с JS библиотеками.
define('ASSETS_JS', ASSETS . 'js/');

// Директория с шаблонами "Themes".
define('THEMES', ASSETS_CSS . 'themes/');

// Директория с изображениями рангов.
define('RANKS_PACK', IMG . 'ranks/');

// Временные константы ( Постоянные времени ) - Минута.
define('MINUTE_IN_SECONDS', 60);

// Временные константы ( Постоянные времени ) - Час.
define('HOUR_IN_SECONDS', 3600);

// Временные константы ( Постоянные времени ) - День.
define('DAY_IN_SECONDS', 86400);

// Временные константы ( Постоянные времени ) - Неделя.
define('WEEK_IN_SECONDS', 604800);

// Временные константы ( Постоянные времени ) - Месяц.
define('MONTH_IN_SECONDS', 2592000);

// Временные константы ( Постоянные времени ) - Год.
define('YEAR_IN_SECONDS', 31536000);

// Регистраниция основных функций.
require INCLUDES . 'functions.php';

// Создание/возобновление сессии.
session_start();

// Включение буферизации.
ob_start();

// Импортирование класса отвечающего за работу с языками и переводами.
use app\ext\Translate;

// Импортирование глобального класса отвечающего за работу с базами данных.
use app\ext\Db;

// Импортирование основного глобального класса.
use app\ext\General;

// Импортирование глобального класса отвечающего за работу с модулями.
use app\ext\Modules;

// Импортирование класса уведомлений.
use app\ext\Notifications;

// Импортирование глобального класса отвечающего за работу с авторизованными пользователями.
use app\ext\Auth;

// Импортирование графического класса.
use app\ext\Graphics;

// __autoload()
spl_autoload_register( function( $class ) {
    $path = str_replace( '\\', '/', $class . '.php' );
    file_exists( $path ) && require $path;
} );

// Создание экземпляра класса работающего с языками и переводами.
$Translate      = new Translate;

// Создание экземпляра класса работающего с базами данных.
$Db             = new Db;

// Создание экземпляра класса уведомлений.
$Notifications  = new Notifications ( $Translate, $Db );

// Создание основного экземпляра класса.
$General        = new General ( $Db );

// Создание экземпляра класса работающего с модулями.
$Modules        = new Modules       ( $General, $Translate, $Notifications );

// Создание экземпляра класса работающего с авторизацией пользователей.
$Auth           = new Auth          ( $General, $Db );

// Создание экземпляра графического класса.
$Graphics       = new Graphics      ( $Translate, $General, $Modules, $Db, $Auth, $Notifications );