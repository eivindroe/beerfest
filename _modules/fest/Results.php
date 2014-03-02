<?php
namespace Beerfest\Fest;

use Beerfest\Fest\Fest;
use Beerfest\Fest\Item\Item;
use Beerfest\Fest\Item\Vote\Votes;
use Beerfest\Core\HtmlList\HtmlList;

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

            $aryItems = array();
            $strContent = '<div id="chart_total"></div>';
            $strNav = '<li onclick="App.chart(\'chart_total\', \'\', [1,2,3]);"><a href="#total">Total</a></li>';
            foreach($aryVotes as $intKey => $objVotes)
            {
                $objItem = $objVotes->getItem();
                $strNav .= '<li onclick="App.chart(\'chart_' . $intKey . '\', \'\', [' . $objItem->getVotesForChart() . ']);"><a href="#chart_' . $intKey . '">' . $objItem->getName() . '</a></li>';
                $aryItems[$objItem->getId()] = $objItem;
                $aryRow = array('name' => $objVotes->getItemName(), 'votes' => $objVotes->getCount(), 'average' => $objVotes->getAverageValue());
                $objRow = $objList->addRow($intKey, $aryRow);
                $objRow->setId($objItem->getCryptId());
                $strContent .= '<div id="chart_' . $intKey . '" style="width: 100%"></div>';
            }
            $strHtml .= $objList->getListHtml();
            //$strHtml .= $strNav . $strContent;
            $strHtml .= '<br />
                        <div data-role="tabs">
                            <div data-role="navbar"><ul>' . $strNav . '</ul></div>
                            ' . $strContent . '
                        </div>';
        }
        return $strHtml;
    }// getHtml


}// Result