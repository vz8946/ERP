<?php
class Admin_SitemapController extends Zend_Controller_Action 
{
    /**
     * 允许操作的管理员列表
     * @var array
     */
    private $_allowDoList = array ('root'=>1);   
	/**
     * 对象初始化
     *
     * @return   void
     */
	public function init() 
	{
        
	}    
    /**
     * 生成站点地图
     *
     * @return void
     */
    public function doBuildAction()
    {
        //在此控制访问权限
        $auth = Admin_Models_API_Auth :: getInstance() -> getAuth();
        if (!isset($this -> _allowDoList[$auth['admin_name']])) {
            echo '<font color="red">你没有该权限！该操作牵涉google收录，基于安全考虑只有超级账户可以操作。</font>';            
		} else {
            $_sitemap = new Admin_Models_API_Sitemap();
            $domain = 'http://122.114.123.95:8099';
            $today  =  date('Y-m-d');
            $gsm =new GoogleSitemap();
            $gsmItem = new GoogleSitemapItem($domain, $today, 'weekly', '0.9');
            $gsm -> addItem ( $gsmItem );
            // 商品分类页
            $catList = $_sitemap -> getGoodsCatsList();
            if ($catList) {
                foreach ($catList as $val) {
                    $gsmItem =new GoogleSitemapItem($domain . '/gallery-'.$val['cat_id'].'.html', $today, 'weekly', '0.8');
                    $gsm -> addItem ( $gsmItem );
                }
            }
            unset($catList);
            // 单品页
            $goodsList = $_sitemap -> getGoodsList();
            if ($goodsList) {
                foreach ($goodsList as $val) {
                    $gsmItem = new GoogleSitemapItem($domain . '/goods-'. $val['goods_id'].'.html', $today, 'weekly', '0.8');
                    $gsm -> addItem ( $gsmItem );
                }
            }
            unset($goodsList);
            // 文章页
            $articleList = $_sitemap -> getArticleList();
            if ($articleList) {
                foreach ($articleList as $val) {
                    $gsmItem = new GoogleSitemapItem($domain . '/help-'. $val['article_id'].'.html', $today, 'weekly', '0.7');
                    $gsm -> addItem ( $gsmItem );
                }
            }
            unset($articleList);
            if ($gsm -> build(Zend_Registry::get('systemRoot').'/www/Sitemap.xml')) {
                $this -> view -> buildOk = 1;
            } else {
                $this -> view -> buildOk = 0;
            }
        }
    }
}

/**
 * GoogleSitemap
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 */
class GoogleSitemap
{
    public $header = "<\x3Fxml version=\"1.0\" encoding=\"UTF-8\"\x3F>\n\t<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
    public $charset = "UTF-8";
    public $footer = "\t</urlset>\n";
    public $items = array();
    public function __construct() {
        
    }
    /**
    * Adds a new item to the channel contents.
    * @param google_sitemap item $new_item
    * @access public
    */
    function addItem($newItem) {
        //Make sure $new_item is an 'google_sitemap item' object
        if(!is_a($newItem, "GoogleSitemapItem")){
          //Stop execution with an error message
          trigger_error("Can't add a non-GoogleSitemapItem object to the sitemap items array");
        }
        $this->items[] = $newItem;
    }
    /**
    * Generates the sitemap XML data based on object properties.
    * @param string $fileName ( optional ) if file name is supplied the XML data is saved in it otherwise returned as a string.
    * @access public
    * @return [void|string]
    */
    function build( $fileName = null ) {
        $map = $this->header . "\n";
        foreach($this->items as $item) {
            $item->loc = htmlentities($item->loc, ENT_QUOTES);
            $map .= "\t\t<url>\n\t\t\t<loc>$item->loc</loc>\n";
            // lastmod
            if ( !empty( $item->lastmod ) ) {
                $map .= "\t\t\t<lastmod>$item->lastmod</lastmod>\n";
            }
            // changefreq
            if ( !empty( $item->changefreq ) ) {
                $map .= "\t\t\t<changefreq>$item->changefreq</changefreq>\n";
            }
            // priority
            if ( !empty( $item->priority ) ) {
                $map .= "\t\t\t<priority>$item->priority</priority>\n";
            }
            $map .= "\t\t</url>\n\n";
        }
        $map .= $this->footer . "\n";
        if (!is_null($fileName)) {
            return file_put_contents($fileName, $map);
        } else {
            return $map;
        }
    }
}

/** 
 * A class for storing google_sitemap items and will be added to google_sitemap objects.
 * @author Svetoslav Marinov <svetoslav.marinov@gmail.com>
 * @copyright 2005
 * @access public
 * @package google_sitemap_item
 * @link http://devquickref.com
 * @version 0.1
*/
class GoogleSitemapItem
{
    /** 
     * Assigns constructor parameters to their corresponding object properties.
     * @access public
     * @param string $loc location
     * @param string $lastmod date (optional) format in YYYY-MM-DD or in "ISO 8601" format
     * @param string $changefreq (optional)( always,hourly,daily,weekly,monthly,yearly,never )
     * @param string $priority (optional) current link's priority ( 0.0-1.0 )
     */
    public $loc = '';
    public $lastmod = '';
    public $changefreg = '';
    public $priority = '';
    public function __construct( $loc, $lastmod = '', $changefreq = '', $priority = '' ) {
        $this->loc = $loc;
        $this->lastmod = $lastmod;
        $this->changefreq = $changefreq;
        $this->priority = $priority;
    }
}