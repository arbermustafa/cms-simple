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
use \Thunder\Shortcode\Shortcode\ShortcodeInterface;
use \Thunder\Shortcode\HandlerContainer\HandlerContainer;
use \Thunder\Shortcode\Parser\RegexParser;
use \Thunder\Shortcode\Processor\Processor;
use \Thunder\Shortcode\Syntax\Syntax;
use \App\Service\Slide;
use \App\Service\Category;

class Shortcodes
{
    public $handlers = null;
    public $processor = null;

    public function __construct()
    {
        $this->handlers = new HandlerContainer();
        $this->addShortcodes();
        $this->processor = new Processor(new RegexParser(new Syntax('[', ']', '/', '=', '#')), $this->handlers);
    }

    public function addShortcodes()
    {
        $class = $this;

        $this->handlers->add('slider', function(ShortcodeInterface $s) use ($class)
        {
            return $class->slider($s);
        });

        $this->handlers->add('promobox', function(ShortcodeInterface $s) use ($class)
        {
            return $class->promobox($s);
        });

        $this->handlers->add('newsbox', function(ShortcodeInterface $s) use ($class)
        {
            return $class->newsbox($s);
        });

        $this->handlers->add('one-half', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->handlers->add('one-third', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->handlers->add('one-fourth', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->handlers->add('two-thirds', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->handlers->add('three-fourths', function(ShortcodeInterface $s) use ($class)
        {
            return $class->grid($s);
        });

        $this->handlers->add('contact-form', function(ShortcodeInterface $s) use ($class)
        {
            return $class->contactForm($s);
        });

        $this->handlers->add('gmap', function(ShortcodeInterface $s) use ($class)
        {
            return $class->gmap($s);
        });

        $this->handlers->add('video', function(ShortcodeInterface $s) use ($class)
        {
            return $class->video($s);
        });

        $this->handlers->add('readmore', function(ShortcodeInterface $s) use ($class)
        {
            return $class->readmore($s);
        });
    }

    public function doShortcode($content)
    {
        return $this->processor->process($content);
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
                $html .= '      <li>';

                if ($slide['url'] !== '' && $slide['url'] !== null) {
                    $target = '_self';

                    if (substr($slide['url'], 0, 4) === 'http') {
                        $target = '_blank';
                    }

                    $html .= '      <a href="'. $slide['url'] .'" target="'. $target .'"><img src="/uploads/'. $slide['featured_photo'] .'" alt="'. $slide['title'] .'"></a>';
                } else {
                    $html .= '      <img src="/uploads/'. $slide['featured_photo'] .'" alt="'. $slide['title'] .'">';
                }

                if ($slide['title'] != '*') {
                    $html .= '      <div class="flex-caption">
                                        <h2>'. $slide['title'] .'</h2>
                                        <p>'. $slide['content'] .'</p>
                                    </div>';
                }

                $html .= '      </li>';
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

                    $html .= '<h4 class="post-title" style="margin-bottom: 5px;">';
                    $html .= '<a href="/'. $post['slug'] .'">'. $post['title'] .'</a>';
                    $html .= '</h4>';

                    $html .= ($type == 'news') ? '<div class="post-content"><p style="margin-bottom: 12px;">'. Str::words($post['content'], 20) .'</p></div>' : '';

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

    public function contactForm($s)
    {
        $html = '<div id="contact-notification-box-success" class="notification-box notification-box-success" style="display: none;">
                    <p>Your message has been successfully sent. We will get back to you as soon as possible.</p>
                    <a href="#" class="notification-close notification-close-success">x</a>
                </div>
                <div id="contact-notification-box-error" class="notification-box notification-box-error " style="display: none;">
                    <p>Your message couldn&rsquo;t be sent.</p>
                    <p>Please fill all the required fields! Email must be a valid email address!</p>
                    <p>Please try again.</p>
                    <a href="#" class="notification-close notification-close-error">x</a>
                </div>
                <form id="contact-form" class="content-form" method="post" action="#">
                    <p>
                        <label for="company">Company:</label>
                        <input type="text" id="company" name="company">
                    </p>
                    <p>
                        <label for="name">Name:<span class="note">*</span></label>
                        <input type="text" id="name" name="name" class="required">
                    </p>
                    <p>
                        <label for="email">Email:<span class="note">*</span></label>
                        <input type="email" id="email" name="email" class="required">
                    </p>
                    <p>
                        <label for="message">Questions/Comments/Message:<span class="note">*</span></label>
                        <textarea id="message" name="message" cols="68" rows="8" class="required"></textarea>
                    </p>
                    <p>
                        <!-- Google Recaptcha code START -->
                        <div class="g-recaptcha" data-sitekey="6Le0bgoUAAAAAJ06L7xuPlUmksLYjhvAN8PmBOy4"></div>
                        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
                        <!-- Google Recaptcha code END -->
                    </p>
                    <p style="margin-bottom: 0;">
                        <input id="submit" class="button" type="submit" name="submit" value="Send Message" style="margin-bottom: 0;">
                    </p>
                </form>';

        return $html;
    }

    public function gmap($s)
    {
        $API_KEY = ($s->getParameter('API_KEY')) ?: 'AIzaSyADi2XnRIVQb_6CwgRkWqIZEc8w-pvTKAM';
        $markers = explode('|', $s->getParameter('markers'));



        $html = '<div id="map" style="width: 100%; height: 380px;"></div>
                <script>
                    var infowindow = null;
                    var markers = [];

                    function initGMap()
                    {
                        var map = new google.maps.Map(document.getElementById("map"), {
                            zoom: 3,
                            center: {lat: 45.916766, lng: 49.833984}
                        });

                        infowindow = new google.maps.InfoWindow({
                            content: "Loading..."
                        });';

        foreach($markers as $marker) {
            $data = $this->_processFields(explode(';', $marker));

            $html .= '  var marker = new google.maps.Marker({
                            position: {lat: '. $data[0] .', lng: '. $data[1] .'},
                            html: "<div><h3>'. $data[3] .'</h3>'. $data[4] .'<br>'. $data[5] .'<br>'. $data[6] .'<br>'. $data[7] .'</div>",
                            icon: "/assets/img/marker-'. $data[2] .'.png",
                            map: map
                        });

                        markers.push(marker);

                        google.maps.event.addListener(marker, "click", function()
                        {
                            infowindow.setContent(this.html);
                            infowindow.open(map, this);
                        });';
        }

        $html .= '  }
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key='. $API_KEY . '&callback=initGMap"></script>';

        return $html;
    }

    public function video($s)
    {
        $url = $s->getParameter('url');

        return '<video width="100%" controls>
                    <source src="'. $url .'" type="video/mp4">
                    Your browser does not support the video tag.
                </video>';
    }

    public function readmore($s)
    {
        $class = $s->getParameter('type');

        return '<div class="shorten-'. $class .'">'. $s->getContent() .'</div>';
    }

    private function _processFields(array $data)
    {
        $result = array();

        foreach ($data as $field) {
            if (strpos($field, '@') !== false ) {
                $data = explode(' ', $field);
                $result[] = $data[0] .' <a href=\"mailto:'. $data[1] .'\">'. $data[1] .'</a>';
            } else {
                $result[] = $field;
            }
        }

        return $result;
    }
}
