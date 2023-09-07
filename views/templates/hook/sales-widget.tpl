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

<div id="simsales-notification" class="activo simsalesAnimado fadeOutDown">
    <div class="simsales-profundo">
        <div class="simsales-imagen" style="background-image: url('{$product.image|escape:'htmlall':'UTF-8'}')">
            <a href="{$product.link|escape:'htmlall':'UTF-8'}"></a>
        </div>
        <div class="simsales-contenido">
            <p class="simsales-linea-1">
               Un usuario de {$city} compró
            </p>
            <p class="simsales-linea-2">
                <a href="{$product.link|escape:'htmlall':'UTF-8'}">{$product.name|escape:'htmlall':'UTF-8'}
                </a>
            </p>
            <p class="simsales-linea-4">
                Hace aproximadamente {$time_elapsed}
            </p>
        </div>
        <a class="simsales-llink-entero" href="{$product.link|escape:'htmlall':'UTF-8'}"></a>
    </div>
</div>