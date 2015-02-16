<?php

namespace org\csflu\isms\service\ubt;

use org\csflu\isms\exceptions\ServiceException;
use org\csflu\isms\models\ubt\UnitBreakthrough;
use org\csflu\isms\models\map\StrategyMap;	

/**
 *
 * @author britech
 */
interface UnitBreakthroughManagementService {

	/**
	* Retrieves the UnitBreakthrough entity
	* @param String $id Retrieve by its identifier
	* @return UnitBreakthrough
	*/
	public function getUnitBreakthrough($id);


	/**
	* Insert the UnitBreakthrough with its aligned StrategyMap entity
	* @param UnitBreakthrough $unitBreakthrough
	* @param StrategyMap $strategyMap
	* @return String auto-generated Id
	* @throws ServiceException
	*/
	public function insertUnitBreakthrough(UnitBreakthrough $unitBreakthrough, StrategyMap $strategyMap);
}