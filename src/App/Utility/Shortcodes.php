<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Utility;

use \Thunder\Shortcode\ShortcodeFacade;
use \Thunder\Shortcode\Shortcode\ShortcodeInterface;
use \App\Service\Slide;
use \App\Service\Category;

class Shortcodes
{
    public $facade = null;

    public function __construct()
    {
        $this->facade = new ShortcodeFacade();
        $this->addShortcodes();
    }

    public function addShortcodes()
    {
        $this->facade->addHandler('slider', function(ShortcodeInterface $s)
        {
            return Shortcodes::slider($s);
        });

        $this->facade->addHandler('promobox', function(ShortcodeInterface $s)
        {
            return Shortcodes::promobox($s);
        });

        $this->facade->addHandler('newsbox', function(ShortcodeInterface $s)
        {
            return Shortcodes::newsbox($s);
        });

        $this->facade->addHandler('one-half', function(ShortcodeInterface $s)
        {
            return Shortcodes::grid($s, 'one-half');
        });

        $this->facade->addHandler('one-third', function(ShortcodeInterface $s)
        {
            return Shortcodes::grid($s, 'one-third');
        });

        $this->facade->addHandler('one-fourth', function(ShortcodeInterface $s)
        {
            return Shortcodes::grid($s, 'one-fourth');
        });

        $this->facade->addHandler('two-thirds', function(ShortcodeInterface $s)
        {
            return Shortcodes::grid($s, 'two-thirds');
        });

        $this->facade->addHandler('three-fourths', function(ShortcodeInterface $s)
        {
            return Shortcodes::grid($s, 'three-fourths');
        });
    }

    public function doShortcode($content)
    {
        return $this->facade->process($content);
    }

    public static function slider($s)
    {
        $slides = Slide::getSlides();
        $html = '';

        if ($slides) {
            $html .= '
                <div id="main-slider">
                    <div class="flex-container">
                        <div class="flexslider">
                            <ul class="slides">';

            foreach ($slides as $slide) {
                $html .= '
                                <li>
                                    <img src="/uploads/'. $slide['featured_photo'] .'" alt="'. $slide['title'] .'">
                                    <div class="flex-caption">
                                        <h2>'. $slide['title'] .'</h2>
                                        <p>'. $slide['content'] .'</p>
                                    </div>
                                </li>';
            }

            $html .= '
                            </ul>
                        </div>
                    </div>
                </div>';
        }

        return $html;
    }

    public static function promobox($s)
    {
        return '
            <div>
                <div class="promobox">
                    <div class="promobox-inner">
                        <a class="button large" href="'. sprintf('%s', $s->getParameter('link')) .'">Learn More</a>
                        <div class="with-button">
                            <h2>'. sprintf('%s', $s->getParameter('title')) .'</h2>
                            <p>'. sprintf('%s', $s->getParameter('description')) .'</p>
                        </div>
                        <a class="button large mobile" href="'. sprintf('%s', $s->getParameter('link')) .'">Learn More</a>
                    </div>
                </div>
            </div>';
    }

    public static function newsbox($s)
    {
        $html = '';
        $title = ($s->getParameter('title')) ?: '';
        $id = (int) $s->getParameter('id');
        $items = (int) $s->getParameter('items');

        $category = Category::getCategory($id);

        if ($category) {
            $posts = Category::getCategoryPosts($id, $items);

            if ($posts) {
                foreach($posts as $post) {

                }
            }
        }

        return $html;
    }

    public static function grid($s, $gridClass)
    {
        $last = ($s->getParameter('last')) ? 'column-last': '';

        return '<div class="'. $gridClass . ' '. $last .'">'. $s->getContent() .'</div>';
    }
}
