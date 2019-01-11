<?php

// Weather Widget
function open_weather_widget() {
	register_widget( 'open_weather_widget' );
}

add_action( 'widgets_init', 'open_weather_widget' );

/**
 * Class simple_weather_widget
 */
class open_weather_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'open_weather_widget',

            __('Opem weather widget', 'weather'),

            array('description' => __('Simple widget for weather info', 'weather'),)
        );
    }

    public function widget($args, $instance)
    {
        $api_key = "ccdc905957d5892e8a95033ada8fb380";
        $title = apply_filters('widget_title', $instance['title']);
        $city = strtolower($instance['weather_city']);
        $country = strtolower($instance['weather_country']);

        $user_ip = getenv('REMOTE_ADDR');
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));

        if ($geo["geoplugin_countryCode"] && $geo["geoplugin_city"]) {
            $country = $geo["geoplugin_countryCode"];
            $city = $geo["geoplugin_city"];
        }

        $weather = file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=" . $city . "," . $country . "&lang=en&units=metric&APPID=" . $api_key . "");
        $weather = json_decode($weather, true);

        echo $args['before_widget'];
        $date = date("F j, Y");
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        ?>
        <!--        <span class="weather__title">-->
        <!--            --><?php //printf((__('Weather in %s', 'aquene-child')), $weather['name']);
        ?>
        <!--        </span>-->
        <div class="weather">
            <img class="weather__img" src="https://openweathermap.org/img/w/<?= $weather['weather'][0]['icon'] ?>.png"
                 alt="Weather <?= $weather['name'] ?>" title="Weather <?= $weather['name'] ?>">
            <span class="weather__desc"><?= $weather['weather'][0]['description'] ?></span>
            <span class="weather__temp"><?= $weather['main']['temp'] ?>&deg;C</span>
            <span class="weather__date"><?= $date ?></span>
            <span class="weather__region"><?= $weather['name'] . ', ' . $weather['sys']['country'] ?></span>
        </div>

        <?php
        echo $args['after_widget'];
    }

    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('New title', 'weather');
        }

        $defaults = array(
            'title'                    => 'Weather',
            'weather_id'      => 1,
            'weather_city'    => 'kiev',
            'weather_country' => 'ua',
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                   type="text" value="<?php echo esc_attr($title); ?>"/>

        </p>
        <p>
            <label
                    for="<?php echo esc_attr($this->get_field_id('weather_city')); ?>"><?php _e('City', 'weather'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id('weather_city')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('weather_city')); ?>"
                   value="<?php echo esc_attr($instance['weather_city']); ?>" style="width:100%;"/>
        </p>
        <p>
            <label
                    for="<?php echo esc_attr($this->get_field_id('weather_country')); ?>"><?php _e('Country', 'weather'); ?></label>
            <input id="<?php echo esc_attr($this->get_field_id('weather_country')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('weather_country')); ?>"
                   value="<?php echo esc_attr($instance['weather_country']); ?>" style="width:100%;"/>
        </p>

        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['weather_city'] = (!empty($new_instance['weather_city'])) ? strip_tags($new_instance['weather_city']) : '';
        $instance['weather_country'] = (!empty($new_instance['weather_country'])) ? strip_tags($new_instance['weather_country']) : '';
        return $instance;
    }
}