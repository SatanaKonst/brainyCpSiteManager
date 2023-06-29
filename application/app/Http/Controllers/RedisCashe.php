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

    public function __construct()
    {

        $this->redisInstance = Redis::connection();
    }

    /** Сохранить список сайтов
     * @param array $siteList
     * @return void
     * @throws \RedisException
     */
    public function saveSiteList(array $siteList)
    {
        foreach ($siteList as $site) {
            if (empty($this->redisInstance->get($site))) {

                $this->redisInstance->lPush('siteList', $site);
            }
        }
    }

    /** Получить кешированный список сайтов
     * @return array|\Redis
     * @throws \RedisException
     */
    public function getSiteList()
    {
        $siteList = $this->redisInstance->lRange('siteList', 0, -1);
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
    public function getAllAuthSiteStatus(){
        return $this->redisInstance->hGetAll('authSitesStatus');
    }

    /** Получить префикс ключей в базе
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|\Illuminate\Foundation\Application|mixed
     */
    private function getLaravelRedisPrefix()
    {
        return config('database.redis.options.prefix');
    }


    /** Очистить всю базу
     * @return bool|\Redis
     * @throws \RedisException
     */
    public function clerAllData()
    {
        return $this->redisInstance->flushAll();
    }

}
