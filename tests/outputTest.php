<?php
/**
 * JBZoo SimpleTypes
 *
 * This file is part of the JBZoo CCK package.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package   SimpleTypes
 * @license   MIT
 * @copyright Copyright (C) JBZoo.com,  All rights reserved.
 * @link      https://github.com/JBZoo/SimpleTypes
 * @author    Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

/**
 * Class outputTest
 * @package JBZoo\SimpleTypes
 */
class OutputTest extends PHPUnit
{
    public function testText()
    {
        isBatch(array(
            // eur
            array('10 000.67 €', val('10000.666 eur')->text()),
            array('-10 000.67 €', val('-10000.666 eur')->text()),

            // usd
            array('$10 000.67', val('10000.666 usd')->text()),
            array('-$10 000.67', val('-10000.666 usd')->text()),

            // rub
            array('10 000,67 руб.', val('10000.666 rub')->text()),
            array('-10 000,67 руб.', val('-10000.666 rub')->text()),

            // uah
            array('10 000,67 грн.', val('10000.666 uah')->text()),
            array('-10 000,67 грн.', val('-10000.666 uah')->text()),

            // byr
            array('10 100 Br', val('10000.666 byr')->text()),
            array('-10 000 Br', val('-10000.666 byr')->text()),

            // %
            array('10.67%', val('10.666 %')->text()),
            array('-10.67%', val('-10.666 %')->text()),

            // with converting
            array('$2.00', val('1 eur')->text('usd')),
            array('0.50 €', val('1 usd')->text('eur')),
        ));
    }

    public function testDump()
    {
        isLike('#10000\.666666\d* uah; id=[0-9]*#i', val('10000.666666666 uah')->dump());
        is('10000.666 uah', val('10000.666 uah')->dump(false));
    }

    public function testData()
    {
        isBatch(array(
            array(array('10000.666', 'uah'), val('10000.666 uah')->data()),
            array(array('10000.666', 'uah'), val('10000.666 uah')->data(false)),
            array('10000.666 uah', val('10000.666 uah')->data(true)),
        ));
    }

    public function testNoStyle()
    {
        isBatch(array(
            array('10 000,67', val('10000.666 uah')->noStyle()),
        ));
    }

    public function testHtmlSpan()
    {
        $html = val('100.50 uah')->html('usd');

        // check tag
        isLike('#<span\s#', $html);

        // check important classes
        isLike('#simpleType#', $html);
        isLike('#simpleType-block#', $html);
        isLike('#simpleType-symbol#', $html);
        isLike('#simpleType-value#', $html);
        isLike('#simpleType-money#', $html);

        // attributes
        isLike('#data-simpleType-id="\d*"#', $html);
        isLike('#data-simpleType-value="8\.04"#', $html);
        isLike('#data-simpleType-rule="usd"#', $html);
        isLike('#data-simpleType-orig-value="100\.5"#', $html);
        isLike('#data-simpleType-orig-rule="uah"#', $html);

        // html
        isLike('#<span class="simpleType-symbol">\$</span>#', $html);
        isLike('#<span class="simpleType-value">8\.04</span>#', $html);
    }

    public function testHtmlInput()
    {
        $html = val('100.50 uah')->htmlInput('usd', 'some-param');

        isLike('#<input\s#', $html);

        // check important classes
        isLike('#simpleType#', $html);
        isLike('#simpleType-input#', $html);
        isLike('#simpleType-value#', $html);
        isLike('#simpleType-money#', $html);

        // attributes
        isLike('#name=\"some-param\"#', $html);
        isLike('#data-simpleType-id="\d*"#', $html);
        isLike('#data-simpleType-value="8\.04"#', $html);
        isLike('#data-simpleType-rule="usd"#', $html);
        isLike('#data-simpleType-orig-value="100\.5"#', $html);
        isLike('#data-simpleType-orig-rule="uah"#', $html);
    }

    public function testGetId()
    {
        isTrue(0 < val()->getId());
    }

    public function testGetLogs()
    {
        $logs = val()->logs();
        isTrue(is_array($logs));
    }
}
