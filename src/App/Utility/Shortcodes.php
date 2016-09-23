<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Utility;

use \Illuminate\Support\Str;
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
        $class = $this;

        $this->facade->addHandler('slider', function(ShortcodeInterface $s) use ($class)
        {
            return $class->slider($s);
        });

        $this->facade->addHandler('promobox', function(ShortcodeInterface $s) use ($class)
        {
            return $class->promobox($s);
        });

        $this->facade->addHandler('newsbox', function(ShortcodeInterface $s) use ($class)
        {
            return $class->newsbox($s);
        });

        $this->facade->addHandler('one-half', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->facade->addHandler('one-third', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->facade->addHandler('one-fourth', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->facade->addHandler('two-thirds', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->facade->addHandler('three-fourths', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });
    }

    public function doShortcode($content)
    {
        return $this->facade->process($content);
    }

    public function slider($s)
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

    public function promobox($s)
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

    public function newsbox($s)
    {
        $html = '';
        $id = (int) $s->getParameter('id');
        $type = ($s->getParameter('type')) ?: 'news';
        $items = (int) $s->getParameter('items');
        $category = Category::getCategory($id);
        $title = ($s->getParameter('title')) ? $s->getParameter('title') : $category['title'];

        if ($category) {
            $html .= '<h2>'. $title .' <span class="more">– <a href="/archive/'. $category['slug'] .'/1">Archive »</a></span></h2>';

            $posts = Category::getCategoryPosts($id, $items);

            if ($posts) {
                $html .= '<div class="post-list-home">';

                foreach($posts as $post) {
                    //$html .= '<div class="post-single">';
                    $html .= '<div class="post">';

                    if ($type == 'news' && $post['featured_photo']) {
                        $html .= '<div class="post-single-image post-single-image-home" style="margin-right: 20px; width: 100px; height: 75px; float: left;">';
                        $html .= '<a class="fancybox" href="/uploads/'. $post['featured_photo'] .'" title="'. $post['title'] .'"><span class="overlay zoom"></span><img src="/uploads/'. $post['featured_photo'] .'" alt="'. $post['title'] .'"></a>';
                        $html .= '</div>';
                    }

                    if ($type == 'event') {
                        $html .= '<div class="post-date post-date-home">';
                        $html .= '<span>'. date('M jS, Y', strtotime($post['date'])) .'</span>';
                        $html .= '</div>';
                    }

                    $html .= '<div class="post-body">';

                    if ($type == 'news') {
                        $html .= '<div class="post-date">';
                        $html .= '<span>'. date('M jS, Y', strtotime($post['date'])) .'</span>';
                        $html .= '</div>';
                    }

                    $html .= '<h4 class="post-title">';
                    $html .= '<a href="/'. $post['slug'] .'">'. $post['title'] .'</a>';
                    $html .= '</h4>';

                    $html .= ($type == 'news') ? '<div class="post-content"><p>'. Str::words($post['content'], 20) .'</p></div>' : '';

                    $html .= '</div>';
                    //$html .= '</div>';
                    $html .= '</div>';
                }

                $html .= '</div>';
            }
        }

        return $html;
    }

    public function grid($s)
    {
        $class = $s->getName();
        $class .= ($s->getParameter('last') == 'true') ? ' column-last': '';

        return '<div class="'. $class .'">'. $s->getContent() .'</div>';
    }
}
