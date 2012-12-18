<?php
namespace Core\BetBundle;

use Symfony\Component\DomCrawler\Crawler;

class WebScrapper
{

  public function getPremierLeagueStandings(){
    $crawler = new Crawler(file_get_contents('http://www.fifa.com/associations/association=eng/nationalleague/standings.html'));
    $table = $crawler->filter('table.maNlStandings');
    if (count($table)){
      foreach ($table as $node){
        $node->setAttribute('class', 'table table-hover table-bordered standings');
        $ths = $node->getElementsByTagName('th');
        foreach($ths as $th){
          if ($th->getAttribute('class') == 'maNlStandingsTitleDiff noHover'){
            $th->parentNode->removeChild($th);
          }
        }
        $tds = $node->getElementsByTagName('td');
        foreach($tds as $td){
          if ($td->getAttribute('class') == 'maNlStandingsDiff'){
            $td->parentNode->removeChild($td);
          }
        }
        $tableNode = $node;
      }
      $header = '<h2>Current standings<span class="pull-right update">(Last update: '.date('Y-m-d H:i').')</span></h2>';
      return $header . $tableNode->ownerDocument->saveHTML($tableNode);
    }else{
      return '<div class="alert alert-error"><strong>EPIC FAIL!</strong>
        Scrapping standings table went wrong! :(<br />Go whine about it to the author!</div>';
    }
  }
}
