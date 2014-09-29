<?php

/*
 * This file is part of the Studio Fact package.
 *
 * (c) Kulichkin Denis (onEXHovia) <onexhovia@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Citfact\Logger;

use Bitrix\Main\Entity;
use Bitrix\Main\Config;
use Monolog\Logger;
use Citfact\Logger\Entity\LoggerTable;

class LoggerManager
{
    /**
     * Translates Monolog log levels to html color priorities.
     */
    private static $logLevels = array(
        Logger::DEBUG     => '#cccccc',
        Logger::INFO      => '#468847',
        Logger::NOTICE    => '#3a87ad',
        Logger::WARNING   => '#c09853',
        Logger::ERROR     => '#f0ad4e',
        Logger::CRITICAL  => '#FF7708',
        Logger::ALERT     => '#C12A19',
        Logger::EMERGENCY => '#000000',
    );

    /**
     * Return unique channels for menu
     *
     * @return array
     */
    public static function getUniqChannels()
    {
        $queryBuilder = new Entity\Query(LoggerTable::getEntity());
        $filterResult = $queryBuilder
            ->registerRuntimeField('CHANNEL',
                array('expression' => array('DISTINCT CHANNEL')
            ))
            ->setSelect(array('CHANNEL'))
            ->setOrder('ID')
            ->exec();

        $channelList = array();
        while ($channel = $filterResult->fetch()) {
            $channelList[] = array(
                'text' => $channel['CHANNEL'],
                'url' => sprintf('logger.php?find_channel=%s&set_filter=Y', $channel['CHANNEL']),
            );
        }

        return $channelList;
    }

    /**
     * @param int $time
     * @return string
     */
    public static function getFormatTime($time)
    {
        return FormatDate(Config\Option::get('citfact.logger', 'FORMAT_TIME'), $time);
    }

    /**
     * @param int $level
     * @return string
     */
    public static function getViewColorLevel($level)
    {
        return '<span style="color:#ffffff;padding:5px;background:'.self::$logLevels[$level].'">'.$level.'</span>';
    }

    /**
     * @return array
     */
    public static function getLogsLevel()
    {
        return self::$logLevels;
    }
}