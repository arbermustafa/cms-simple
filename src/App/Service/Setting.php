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
use \Valitron\Validator;

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
        $validator = self::validator($params);

        if ($validator->validate()) {
            try {
                foreach ($params as $values) {
                    SettingModel::updateOrCreate(array('key_name' => $values['key_name']), $values)->toArray();
                }

                $cache->clearByTags(array(__CLASS__));

                return array('success' => 'Settings modified');
            } catch (\Exception $e) {
                $log->error($e);

                return array('error' => 'Settings not modified!');
            }
        } else {
            return array('error' => $validator->errors());
        }
    }

    private static function validator($params)
    {
        $validator = new Validator($params);
        $validator->rule('required', array('*.key_name'));
        $validator->rule('in', '*.key_name', array('description', 'keywords', 'fb', 'in', 'analytics'), true)->message('Invalid value');

        return $validator;
    }
}
