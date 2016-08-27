<?php
/**
 * Europa Re
 *
 * @author      Arber Mustafa <arber9@gmail.com>
 * @version     1.0.0
 * @package     App
 */
namespace App\Service;

use \App\Model\Setting as SettingModel;

class Setting extends Base
{
    public static function getByKey($key_name)
    {
        $cache = self::_getCache();
        $key = __CLASS__.'_'.__FUNCTION__.'_'.$key_name;
        $result = '';

        if (false == ($result = $cache->getItem($key))) {
            $key_data = SettingModel::select('key_value')
                ->where('key_name', $key_name)
                ->first();

            if ($key_data) {
                $result = $key_data;

                $cache->setItem($key, $result);
                $cache->setTags($key, array(__CLASS__));
            }
        }

        return $result;
    }

    public static function edit(array $params)
    {
        $log = self::_getLog();
        $cache = self::_getCache();

        try {
            foreach ($params as $key => $value) {
                SettingModel::updateOrCreate(array('key_name' => $key), array('key_value' => $value));
            }

            $cache->clearByTags(array(__CLASS__));

            return array('success' => 'Settings modified');
        } catch (\Exception $e) {
            $log->error($e);

            return array('error' => 'Settings not modified!');
        }
    }
}
