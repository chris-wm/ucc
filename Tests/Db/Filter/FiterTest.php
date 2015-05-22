<?php

namespace Ucc\Tests\Db\Filter;

use \PHPUnit_Framework_TestCase as TestCase;
use Ucc\Data\Filter\Criterion\Criterion;
use Ucc\Data\Filter\Clause\Clause;
use Ucc\Db\Filter\Sql;
use Ucc\Data\Filter\Filter as Data_Filter;
use Ucc\Db\Filter\Filter as Db_Filter;
use Ucc\Data\Types\Pseudo\FilterType;

class FilterTest extends TestCase
{
    public function filterToSqlProvider()
    {
        $data = array();

        $filter     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-name-eq-value-jane'),
            FilterType::filterToCriterion('and-surname-inci-value-doe'),
        );
        $sql    = '`name` = CAST(:filter_0 AS CHAR) COLLATE utf8_bin AND `surname` LIKE CONCAT("%", :filter_1, "%") COLLATE utf8_general_ci';
        $params = array('filter_0' => 'jane', 'filter_1' => 'doe');
        $clause = new Clause();
        $clause
            ->setStatement($sql)
            ->setParameters($params);
        $filter->setCriterions($criterions);
        $expected   = $clause;
        $data[]     = array($filter, $expected);

        $filter     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-foo-nei-field-bar'),
            FilterType::filterToCriterion('or-loo-ne-field-moo'),
        );
        $sql    = '`foo` != CAST(`bar` AS CHAR) COLLATE utf8_general_ci OR `loo` != CAST(`moo` AS CHAR) COLLATE utf8_bin';
        $clause = new Clause();
        $clause
            ->setStatement($sql);
        $filter->setCriterions($criterions);
        $expected   = $clause;
        $data[]     = array($filter, $expected);

        $filter     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-isActive-bool-field-true'),
            FilterType::filterToCriterion('or-size-gt-field-volume'),
            FilterType::filterToCriterion('and-bar-re-value-^moo$'),
        );
        $sql    = '(`isActive` IS NOT NULL AND `isActive` != "") OR `size` > `volume` AND `bar` REGEXP :filter_2';
        $params = array('filter_2' => '^moo$');
        $clause = new Clause();
        $clause
            ->setStatement($sql)
            ->setParameters($params);
        $filter->setCriterions($criterions);
        $expected   = $clause;
        $data[]     = array($filter, $expected);

        $filter     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-age-in-value-18,19,20,88'),
            FilterType::filterToCriterion('and-city-nbegins-value-London'),
        );
        $sql    = '`age` IN (:filter_0_0 COLLATE utf8_bin, :filter_0_1 COLLATE utf8_bin, :filter_0_2 COLLATE utf8_bin, :filter_0_3 COLLATE utf8_bin) AND `city` NOT LIKE CONCAT(:filter_1, "%") COLLATE utf8_bin';
        $params = array('filter_0_0' => '18', 'filter_0_1' => '19', 'filter_0_2' => '20', 'filter_0_3' => '88', 'filter_1' => 'London');
        $clause = new Clause();
        $clause
            ->setStatement($sql)
            ->setParameters($params);
        $filter->setCriterions($criterions);
        $expected   = $clause;
        $data[]     = array($filter, $expected);

        return $data;
    }

    /**
     * @dataProvider filterToSqlProvider
     */
    public function testFilterToSql($filters, $expected)
    {
        $result = Db_Filter::filterToSqlClause($filters);
        $this->assertEquals($expected, $result);
    }

    public function filtersToSqlProvider()
    {
        $filterA     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-isActive-bool-field-true'),
            FilterType::filterToCriterion('or-size-gt-field-volume'),
            FilterType::filterToCriterion('and-bar-re-value-^moo$'),
        );
        $filterA->setCriterions($criterions);

        $filterB     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-age-in-value-18,19,20,88'),
            FilterType::filterToCriterion('and-city-nbegins-value-London'),
        );
        $filterB->setCriterions($criterions);

        $filterC     = new Data_Filter();
        $criterions = array(
            FilterType::filterToCriterion('and-foo-nei-field-bar'),
            FilterType::filterToCriterion('or-loo-ne-value-7'),
        );
        $filterC
            ->setCriterions($criterions)
            ->setLogic('or');

        $sql = '((`isActive` IS NOT NULL AND `isActive` != "") OR `size` > `volume` AND `bar` REGEXP :0_filter_2) AND (`age` IN (:1_filter_0_0 COLLATE utf8_bin, :1_filter_0_1 COLLATE utf8_bin, :1_filter_0_2 COLLATE utf8_bin, :1_filter_0_3 COLLATE utf8_bin) AND `city` NOT LIKE CONCAT(:1_filter_1, "%") COLLATE utf8_bin) OR (`foo` != CAST(`bar` AS CHAR) COLLATE utf8_general_ci OR `loo` != CAST(:2_filter_1 AS CHAR) COLLATE utf8_bin)';
        $params = array(
            '0_filter_2'    => '^moo$',
            '1_filter_0_0'  => '18',
            '1_filter_0_1'  => '19',
            '1_filter_0_2'  => '20',
            '1_filter_0_3'  => '88',
            '1_filter_1'    => 'London',
            '2_filter_1'    => '7',
        );

        $clause = new Clause();
        $clause
            ->setStatement($sql)
            ->setParameters($params);

        $data[]     = array(array($filterA, $filterB, $filterC), $clause);

        return $data;
    }

    /**
     * @dataProvider filtersToSqlProvider
     */
    public function testfiltersToSqlClause($filters, $expected)
    {
        $result = Db_Filter::filtersToSqlClause($filters);
        $this->assertEquals($expected, $result);
    }
}
