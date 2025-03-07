<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Mockery\Exception;

/**
 * https://docs.brainycp.com/index.php/API:_%D1%83%D0%BF%D1%80%D0%B0%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5_%D0%B2%D0%B5%D0%B1-%D1%81%D0%B5%D1%80%D0%B2%D0%B5%D1%80%D0%BE%D0%BC
 */
class BrainyApi extends Controller
{
    private $panelUrl = null;
    private $panelLogin = null;

    private $panelPass = null;

    private const API_POINT = '/api/api.php';

    public function __construct()
    {
        if ( empty($_ENV['PANEL_URL']) || empty($_ENV['PANEL_LOGIN'] || empty($_ENV['PANEL_PASS'])) ) {
            throw new Exception('Error get connection data');
        }
        $this->panelUrl = $_ENV['PANEL_URL'];
        $this->panelLogin = $_ENV['PANEL_LOGIN'];
        $this->panelPass = $_ENV['PANEL_PASS'];
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        return new Client([
            'verify' => false
        ]);
    }

    /** Получить список сайтов
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getSiteList($user = '')
    {
        $params = [
            'login' => $this->panelLogin,
            'pass' => $this->panelPass,
            'module' => 'apacserver',
            'subdo' => 'list_http',

        ];
        if ( !empty($user) ) {
            $params['user_edit'] = $user;
        }
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => $params
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( !empty($content->detail) ) {
                asort($content->detail);
                return $content->detail;
            }
        }
        return [];
    }

    public function getAllSites()
    {
        $hostAacc = $this->getHostAccounts();
        $sites = [];
        foreach ($hostAacc as $host) {
            $tmp = $this->getSiteList($host);
            if ( !empty($tmp) ) {
                $sites[] = $tmp;
            }
        }
        return $sites;
    }

    public function getHostAccounts()
    {
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'hostacc',
                    'subdo' => 'listuseracc'
                ]
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( !empty($content->data) ) {
                return $content->data;
            }
        }
        return [];
    }

    /** Получить список баз
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDatabasesList()
    {
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'dbusage',
                    'subdo' => 'show_db'
                ]
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( !empty($content->array) ) {
                return $content->array;
            }
        }
        return [];
    }

    /** TODO:: Проблема с установкой. Пишет, что поставлено, но пароля нет
     * @param $directory
     * @param $password /home/UserNamePanel/sites/domain
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function setDirPassword($directory = '/', $password = '')
    {
        if ( empty($password) ) {
            $password = $_ENV['DEFAULT_DIR_PASSWORD'];
        }
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'apacserver',
                    'subdo' => 'set_password',
                    'directory' => $directory,
                    'password' => $password,
                ]
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( $content->code === 0 ) {
                return true;
            } else {
                throw new Exception('Error set password ' . $content->message);
            }
        }
        return false;
    }

    /** Удаляет пароль с директории
     * @param $directory
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteDirPassword($directory)
    {
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'apacserver',
                    'subdo' => 'delete_password',
                    'directory' => $directory,
                ]
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( $content->code === 0 ) {
                return true;
            } else {
                throw new Exception('Error delete password ' . $content->message);
            }
        }
        return false;
    }

    /** Добавить базу
     * @param string $name
     * @param string $pass
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addDatabase(string $name, string $pass)
    {
        if ( mb_strlen($name) > 7 ) {
            throw new Exception('Value must not exceed 7 characters');
        }
        /*
         * Добавим базу
         */
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'dbusage',
                    'subdo' => 'add_db',
                    'name_db' => $name
                ]
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( $content->code === 0 ) {
                $dbName = $content->database;
            } else {
                throw new Exception('Error add db ' . $content->message);
            }
        }

        /**
         * Добавим пользователя
         */
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'dbusage',
                    'subdo' => 'add_user',
                    'login_user' => $name,
                    'password_user' => $pass
                ]
            ]
        );

        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( !empty($content->code === 0) ) {
                $dbUser = $content->dbuser;
            } else {
                throw new Exception('Error add user db ' . $content->message);
            }
        }

        /**
         * Применим привелегии
         */
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'dbusage',
                    'subdo' => 'add_user_db',
                    'name_user' => $dbUser,
                    'name_db' => $dbName,
                    'privilegies' => 'SELECT,INSERT,UPDATE,DELETE,CREATE,ALTER,INDEX,DROP,CREATE TEMPORARY TABLES,CREATE ROUTINE,ALTER ROUTINE,EXECUTE,CREATE VIEW,EVENT,TRIGGER,REFERENCES,LOCK TABLES,SHOW VIEW'
                ]
            ]
        );

        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( $content->code === 0 ) {
                return true;
            }
        }
        return false;
    }

    /** Добавить домен
     * @param string $domain
     * @param bool $addWwwAlias
     * @param string $phpVersion
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addDomain(string $domain, bool $addWwwAlias = true, string $phpVersion = 'php81w')
    {
        $aliases = '';
        if ( $addWwwAlias === true ) {
            $aliases = 'www.' . $domain;
        }
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => [
                    'login' => $this->panelLogin,
                    'pass' => $this->panelPass,
                    'module' => 'server_control',
                    'subdo' => 'add_domain',
                    'domains' => $domain,
                    'aliases' => $aliases,
                    'dir' => $domain,
                    'php_version' => $phpVersion
                ]
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            if ( $content->code === 201 ) {
                return true;
            }
        }
        return false;
    }

    /** Получить список запароленых дирректорий
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getPasswdDir($user = '')
    {
        $param = [
            'login' => $this->panelLogin,
            'pass' => $this->panelPass,
            'module' => 'apacserver',
            'subdo' => 'list_directory'
        ];
        if ( !empty($user) ) {
            $param['user_edit'] = $user;
        }
        $response = $this->getClient()->post(
            $this->panelUrl . self::API_POINT,
            [
                'form_params' => $param
            ]
        );
        $content = trim($response->getBody()->getContents());
        if ( !empty($content) ) {
            $content = json_decode($content);
            $result = array();
            if ( !empty($content->detail) ) {
                foreach ($content->detail as $folder) {
                    $domain = $folder[3];
                    $result[$domain][] = $folder[0];
                }
                ksort($result);
                return $result;
            }
        }
        return [];
    }

    /** Добавляет сайт
     * @param $domain
     * @param $dbPass
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addSite($domain, $dbName, $dbPass, $setDirPassword = false)
    {
        if ( empty($_ENV['PANEL_LOGIN']) ) {
            throw new \Exception('Пустой логин панели');
        }

        //Добавляем сайт
        if ( $this->addDomain($domain) ) {
            if ( $setDirPassword ) {
                //TODO:: ОТключено до ответа на форуме или обновления панелиы
//                $setPassResult = $this->setDirPassword(
//                    '/home/' . $_ENV['PANEL_LOGIN'] . '/sites/' . $domain . '/'
//                );
//                if ($setPassResult === false) {
//                    throw new \Exception('Ошибка установки пароля на сайт');
//                }
            }

            //Добавляем базу
            if ( $this->addDatabase($dbName, $dbPass) ) {
                return $_ENV['PANEL_LOGIN'] . '_' . $dbName;
            }
            throw new \Exception('Ошибка добавления базы');
        }
        throw new \Exception('Ошибка добавления домена');
    }
}
