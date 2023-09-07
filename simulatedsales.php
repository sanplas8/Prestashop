<?php
/**
 * 2007-2023 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2023 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

class Simulatedsales extends Module implements WidgetInterface
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'simulatedsales';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'Santiago Plastine';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans('Simulated Sales', array(), 'Module.simulatedsales.Admin');
        $this->description = $this->getTranslator()->trans('Modulo que genera pop up con ventas simuladas', array(), 'Module.simulatedsales.Admin');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }


    public function install()
    {
        Configuration::updateValue('SIMULATEDSALES_LIVE_MODE', false);

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('displayBackOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('SIMULATEDSALES_LIVE_MODE');

        return parent::uninstall();
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addCSS($this->_path . '/views/css/simulatedsales-styles.css');
    }

    // public function hookDisplayBeforeBodyClosingTag()
    //{
    //   $this->context->controller->addJS($this->_path . '/views/js/front.js');
    //}

    /**
     * Funciones para obtener los datos
     */

    public function getRandomProduct()
    {
        $id_lang = (int) $this->context->language->id;

        // Consulta SQL para seleccionar un producto aleatorio
        $sql = "SELECT p.id_product, pl.link_rewrite
                 FROM " . _DB_PREFIX_ . "product p
                 LEFT JOIN " . _DB_PREFIX_ . "product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = $id_lang)
                 WHERE p.active = 1
                 ORDER BY RAND()
                 LIMIT 1";

        $result = Db::getInstance()->executeS($sql);

        if (!empty($result)) {
            $product = new Product((int) $result[0]['id_product']);
            $link = $this->context->link->getProductLink($product, $result[0]['link_rewrite']);

            return [
                'name' => $product->name[$id_lang],
                'link' => $link,
                'image' => $product->getCoverWs([ImageType::getFormattedName('small')])['bySize']['small']['url'],
            ];
        }

        return null;
    }

    /**
     * Función para obtener una ciudad aleatoria de Argentina
     */
    public function getRandomCityArgentina()
    {
        $cities = [
            'Ciudad 1',
            'Ciudad 2',
            // Agrega más ciudades aquí
        ];

        $randomCityIndex = array_rand($cities);

        return $cities[$randomCityIndex];
    }

    /**
     * Función para generar un tiempo transcurrido aleatorio
     */
    public function generateRandomTimeElapsed()
    {
        $seconds = rand(60, 60 * 60 * 24 * 15); // De 1 minuto a 15 días en segundos

        if ($seconds < 60) {
            return $seconds . ' segundos';
        } elseif ($seconds < 3600) {
            return floor($seconds / 60) . ' minutos';
        } elseif ($seconds < 86400) {
            return floor($seconds / 3600) . ' horas';
        } else {
            return floor($seconds / 86400) . ' días';
        }
    }

    /**
     * Función para renderizar el widget
     */
    public function renderWidget($hookName, array $configuration)
    {
        $this->context->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        $template = 'module:' . $this->name . '/views/templates/hook/sales-widget.tpl';
        return $this->fetch($template);
    }

    /**
     * Función para obtener las variables necesarias para el widget
     */
    public function getWidgetVariables($hookName, array $configuration)
    {
        $product = $this->getRandomProduct();
        $city = $this->getRandomCityArgentina();
        $time_elapsed = $this->generateRandomTimeElapsed();

        return [
            'product' => $product,
            'city' => $city,
            'time_elapsed' => $time_elapsed,
        ];
    }
}