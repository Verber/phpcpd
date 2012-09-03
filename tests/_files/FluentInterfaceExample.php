<?php
/**
 * Created by JetBrains PhpStorm.
 * User: verber
 * Date: 03.09.12
 * Time: 21:55
 * To change this template use File | Settings | File Templates.
 */
class FluentInterfaceExample {

  public function testViewChallengedModeFilter() {
    $searchParams['expanded']['view_patent_type'] = App_Data_Search_Abstract::FILTER_BY_CHALLENGED_PATENTS;


    $search = $this->_getBaseSearch();
    $query = $this->_getBaseInnerJoinQuery(
      array(
           0 => 'gpf.GridPatent_8 gp',
           1 => 'gp.GridPatent_Expiry_Coverage gpec',
           2 => 'gpec.GridPatentExpiry gpe',
           3 => 'gpe.GridPatentStatus gps'
      )
    );
    $query->expects($this->at(4))->method('leftJoin')->with($this->equalTo('gpe.GridPatentStatus_5 gps2'));

    $query->expects($this->at(5))->method('innerjoin')->with('gpe.GridPatentExpiry_Litigation gpel');
    $query->expects($this->exactly(6))->method('addWhere')->with(
      $this->logicalOr(
        $this->equalTo('gp.active = ?'),
        $this->equalTo('gpec.active = ?'),
        $this->equalTo('gpe.active = ?'),
        $this->equalTo('gps.active = ?'),
        $this->equalTo('gps2.active = ?'),
        $this->equalTo('gpel.active = ?')

      ),
      $this->equalTo('Y')
    );

    $query->expects($this->at(12))->method('andWhere')->with($this->equalTo('gpe.id = gpec.expiry_id'));
    $query->expects($this->at(13))->method('andWhere')->with(
      'gps.status = ?', App_Data_Search_Abstract::PATENT_STATUS_CHALLENGER
    );
    $query->expects($this->at(14))->method('orWhere')->with(
      'gps2.status = ?', App_Data_Search_Abstract::PATENT_STATUS_CHALLENGER
    );

    $search->setQuery($query);
    $search->setSearchParams($searchParams);
    $search->setUserId(1);
    $search->addViewModeFilter();

  }

  public function testViewWithdrawnModeFilter()
  {
    $searchParams['expanded']['view_patent_type'] = App_Data_Search_Abstract::FILTER_BY_WITHDRAWN_PATENTS;


    $search = $this->_getBaseSearch();
    $query = $this->_getBaseInnerJoinQuery(
      array(
           0 => 'gpf.GridPatent_8 gp',
           1 => 'gp.GridPatent_Expiry_Coverage gpec',
           2 => 'gpec.GridPatentExpiry gpe',
           3 => 'gpe.GridPatentStatus gps'
      )
    );
    $query->expects($this->at(4))->method('leftJoin')->with($this->equalTo('gpe.GridPatentStatus_5 gps2'));

    $query->expects($this->at(5))->method('innerjoin')->with('gpe.GridPatentExpiry_Litigation gpel');
    $query->expects($this->exactly(6))->method('addWhere')->with(
      $this->logicalOr(
        $this->equalTo('gp.active = ?'),
        $this->equalTo('gpec.active = ?'),
        $this->equalTo('gpe.active = ?'),
        $this->equalTo('gps.active = ?'),
        $this->equalTo('gps2.active = ?'),
        $this->equalTo('gpel.active = ?')

      ),
      $this->equalTo('Y')
    );

    $query->expects($this->at(12))->method('andWhere')->with($this->equalTo('gpe.id = gpec.expiry_id'));
    $query->expects($this->at(13))->method('andWhere')->with('gps.status = ?', App_Data_Search_Abstract::PATENT_STATUS_WITHDRAWN);
    $query->expects($this->at(14))->method('orWhere')->with('gps2.status = ?', App_Data_Search_Abstract::PATENT_STATUS_WITHDRAWN);

    $search->setQuery($query);
    $search->setSearchParams($searchParams);
    $search->setUserId(1);
    $search->addViewModeFilter();

  }
}
