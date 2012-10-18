<?php
/**
 * NOVIUS OS - Web OS for digital communication
 *
 * @copyright  2011 Novius
 * @license    GNU Affero General Public License v3 or (at your option) any later version
 *             http://www.gnu.org/licenses/agpl-3.0.html
 * @link http://www.novius-os.org
 */

namespace Nos;

class Tools_Url
{
    /**
     * get absolute url of a page by his id
     *
     * @param int $page_id
     * @return mixed Can be null if page not found
     */
    public static function page($page_id)
    {
        if (is_numeric($page_id)) {
            //Test if page_id is enhanced then no request
            \Config::load(APPPATH.'data'.DS.'config'.DS.'url_enhanced.php', 'data::url_enhanced');
            $url_enhanced = \Config::get('data::url_enhanced', array());
            $page_params = \Arr::get($url_enhanced, $page_id, false);
            if ($page_params) {
                return Tools_Url::context($page_params['context']).$page_params['url'];
            }

            $page = static::find($page_id);
            if (!empty($page)) {
                return $page->url();
            }
        }
        return null;
    }

    /**
     * get absolute url of the context's home page
     *
     * @param string $context
     * @return string
     */
    public static function context($context)
    {
        $contexts = Tools_Context::contexts();
        if (!empty($contexts[$context])) {
            $base_urls = $contexts[$context];
            if (count($base_urls) > 1 && is_a(Nos::main_controller(), 'Nos\Controller_Front') && in_array(Nos::main_controller()->getContextUrl(), $base_urls)) {
                return Nos::main_controller()->getContextUrl();
            } else {
                return $base_urls[0];
            }
        }
        return Uri::base(false);
    }
}
