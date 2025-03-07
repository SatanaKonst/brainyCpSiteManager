<?php

namespace App\Http\Controllers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Redis;

class RedisCashe extends Controller
{
    /**
     * @var \Redis
     */
    private $redisInstance = null;

    private const TTL_TIME = 10800;

    public function __construct()
    {

        $this->redisInstance = Redis::connection();
        if ($this->needUpdateCashe()) {
            $this->clerAllData();
        }
    }

    /** Сохранить список сайтов
     * @param array $siteList
     * @return void
     * @throws \RedisException
     */
    public function saveSiteList($user, array $siteList)
    {
        foreach ($siteList as $site) {
            if (empty($this->redisInstance->get($site))) {
                $this->redisInstance->lPush($user.'_siteList', $site);
            }
        }
    }

    /** Получить кешированный список сайтов
     * @return array|\Redis
     * @throws \RedisException
     */
    public function getSiteList($user)
    {
        $siteList = $this->redisInstance->lRange($user.'_siteList', 0, -1);
        asort($siteList);
        return $siteList;
    }

    /** Сохранить статус авторизации к сайту
     * @param string $domain
     * @param bool $status
     * @return bool|int|\Redis
     * @throws \RedisException
     */
    public function saveAuthSitesStatus(string $domain, bool $status = false)
    {
        return $this->redisInstance->hSet('authSitesStatus', $domain, ($status === true) ? 'Y' : 'N');
    }

    /** Получить статус авторизации на конкретном сайте
     * @param string $domain
     * @return false|\Redis|string
     * @throws \RedisException
     */
    public function getAuthSiteStatus(string $domain)
    {
        return $this->redisInstance->hGet('authSitesStatus', $domain);
    }

    /** Получить статусы авторизации по всем сайтам
     * @return array|false|\Redis
     * @throws \RedisException
     */
    public function getAllAuthSiteStatus()
    {
        return $this->redisInstance->hGetAll('authSitesStatus');
    }

    /** Получить префикс ключей в базе
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed
     */
    private function getLaravelRedisPrefix()
    {
        return config('database.redis.options.prefix');
    }

    /** Cохранить запароленые дирректории
     * @param array $dirs
     * @return void
     * @throws \RedisException
     */
    public function savePasswdDir($user,array $dirs)
    {
        foreach ($dirs as $domain => $dir) {
            $this->redisInstance->hSet($user.'_passwdDirs', $domain, json_encode($dir));
        }
    }

    /** Вернуть запароленые дирректории
     * @param $domain
     * @return false|\Redis|string
     * @throws \RedisException
     */
    public function getPasswdDirs($domain)
    {
        $result = $this->redisInstance->hGet('passwdDirs', $domain);

        return json_decode($result, true);
    }

    public function getAllPasswdDirs($user)
    {
        $result = $this->redisInstance->hGetAll($user.'_passwdDirs');
        if (!empty($result)) {
            foreach ($result as $index => $item) {
                $result[$index] = json_decode($item, true);
            }
        }
        return $result;
    }

    /** Очистить всю базу
     * @return bool|\Redis
     * @throws \RedisException
     */
    public function clerAllData()
    {
        $result = $this->redisInstance->flushAll();
        $this->setLastUpdateTime();
        return $result;
    }

    private function setLastUpdateTime()
    {
        $this->redisInstance->set('lastUpdateTime', time());
    }

    /** Получить время последнего обновления кеша
     * @return false|mixed|\Redis|string
     * @throws \RedisException
     */
    private function getLastUpdateTime()
    {
        return $this->redisInstance->get('lastUpdateTime');
    }

    /** Проверка нужно ли принудительное обновление кеша
     * @return bool
     */
    private function needUpdateCashe()
    {
        $lastUpdateTime = $this->getLastUpdateTime();
        if (empty($lastUpdateTime)) {
            return true;
        }
        $currentTime = time();
        return ($currentTime - $lastUpdateTime) > self::TTL_TIME;
    }

}
