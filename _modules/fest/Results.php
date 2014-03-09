<?php
namespace Beerfest\Fest;

use Beerfest\Fest\Fest;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\Vote\Votes;
use Beerfest\Core\HtmlList\HtmlList;
use Beerfest\Core\Button;

class Result
{
    /**
     * Fest object
     * @var Fest
     */
    private $objFest;


    /**
     * Fest items votes
     * @var array
     */
    private $aryVotes;


    /**
     * Constructor
     *
     * @param Fest $objFest Fest object
     *
     * @since 29. February 2014, v. 1.00
     * @return Result
     */
    public function __construct(Fest $objFest)
    {
        $this->objFest = $objFest;
        return $this;
    }// __construct


    /**
     * Get fest object
     *
     * @since 29. February 2014, v. 1.00
     * @return Fest
     */
    private function getFest()
    {
        return $this->objFest;
    }// getFest


    /**
     * Get fest votes
     *
     * @since 29. February 2014, v. 1.00
     * @return array Votes
     */
    private function getVotes()
    {
        if(!isset($this->aryVotes))
        {
            $aryVotes = array();
            $objFest = $this->getFest();
            $aryItems = $objFest->getItems();

            if(count($aryItems))
            {
                $aryVotes = array();
                foreach($aryItems as $objItem)
                {
                    $aryVotes[] = $objItem->getVotes();
                }
            }
            $this->aryVotes = $aryVotes;
        }
        return $this->aryVotes;
    }// getVotes


    /**
     * Get fest result as html
     *
     * @since 29. February 2014, v. 1.00
     * @return string Fest result as html
     */
    public function getHtml()
    {
        $strHtml = '';
        $aryVotes = $this->getVotes();

        if(count($aryVotes))
        {
            $objList = new HtmlList('Item', _FEST_RESULT);
            $objList->addColumn('name', _ITEM_NAME);
            $objCount = $objList->addColumn('votes', _VOTES);
            $objCount->setAlignment($objCount::ALIGN_CENTER);
            $objCount->setPriority($objCount::PRIORITY_2);
            $objAverage = $objList->addColumn('average', _ITEM_AVERAGE_VALUE);
            $objAverage->setAlignment($objAverage::ALIGN_CENTER);

            $objView = $objList->addColumn('view', _VIEW_RESULTS);
            $objView->setAlignment($objView::ALIGN_CENTER);

            $arySeries = array();
            foreach($aryVotes as $intKey => $objVotes)
            {
                $objItem = $objVotes->getItem();
                $strVotes = $objItem->getVotesForChart();
                $arySeries[] = '[\'' . $objItem->getName() . '\', ' . $objVotes->getAverageValue() . ']';
                $objButton = new Button('view', _VIEW);
                $objButton->setAttributes(array('onclick' => "App.chart('" . $objItem->getName() . "', " . $strVotes . ");"));
                $aryRow = array(
                    'name'      => $objVotes->getItemName(),
                    'votes'     => $objVotes->getCount(),
                    'average'   => $objVotes->getAverageValue(),
                    'view'      => $objButton->getHtml()
                );
                $objRow = $objList->addRow($intKey, $aryRow);
                $objRow->setId($objItem->getCryptId());
            }

            $objTotal = new Button('total', 'Total');
            $objTotal->setInline(true);
            $objTotal->setAttributes(array('onclick' => 'App.chart(\'Total\', [' . implode(', ', $arySeries) . '])'));
            $strHtml .= $objTotal->getHtml();
            $strHtml .= $objList->getListHtml();
            $strHtml .= '<div id="result"><div id="result-chart" class="center"></div></div>';
        }
        return $strHtml;
    }// getHtml


}// Result