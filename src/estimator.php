<?php

function infectionProjection(int $currentlyInfected, array $data)
{
	$timeToElape = normalizeDuration($data["timeToElapse"], $data["periodType"]);
  	
	return $currentlyInfected * (pow(2 , intdiv($timeToElape, 3)));
}


function normalizeDuration(int $timeToElapse, String $periodType)
{
	if ($periodType === 'weeks') {
    	return $timeToElapse * 7;
	} 
	 if ($periodType === 'months') {
		return $timeToElapse * 30;
	} 
	
	return $timeToElapse ;
	
}

function calcBedAvailability($totalBeds)
{
	return floor((35/100) * $totalBeds);
}

function calcNoramlizedAvgIncome($data)
{
	return  $data["region"]["avgDailyIncomeInUSD"] * $data["region"]["avgDailyIncomePopulation"] * normalizeDuration($data["timeToElapse"], $data["periodType"]) ;
}

function impactEstimator($data)
{
	$impact = $data["reportedCases"] * 10;
	
	$infectionsByRequestedTime = infectionProjection($impact, $data );
	$severeCasesByRequestedTime = $infectionsByRequestedTime * 0.15;
	$hospitalBedsByRequestedTime = round(calcBedAvailability($data["totalHospitalBeds"]) - $severeCasesByRequestedTime, 0, PHP_ROUND_HALF_DOWN);
	$casesForICUByRequestedTime = $infectionsByRequestedTime * 0.05;
	$casesForVentilatorsByRequestedTime =  $infectionsByRequestedTime * 0.02;
	$dollarsInFlight = round($infectionsByRequestedTime * calcNoramlizedAvgIncome($data), 2);
	
	return [
		  "currentlyInfected" => $impact,
		  "infectionsByRequestedTime" => $infectionsByRequestedTime,
		  "severeCasesByRequestedTime" => $severeCasesByRequestedTime,
		  "hospitalBedsByRequestedTime"=> $hospitalBedsByRequestedTime,
		  "casesForICUByRequestedTime" => $casesForICUByRequestedTime,
		  "casesForVentilatorsByRequestedTime" =>$casesForVentilatorsByRequestedTime,
		  "dollarsInFlight" =>$dollarsInFlight,
	   ];
}


function severeImpactEstimator($data)
{
	$impact = $data["reportedCases"] * 50;

	$infectionsByRequestedTime = infectionProjection( $impact, $data);
	$severeCasesByRequestedTime = $infectionsByRequestedTime * 0.15;
	$hospitalBedsByRequestedTime = round(calcBedAvailability($data["totalHospitalBeds"]) - $severeCasesByRequestedTime, 0 , PHP_ROUND_HALF_DOWN);
	$casesForICUByRequestedTime = $infectionsByRequestedTime * 0.05;
	$casesForVentilatorsByRequestedTime =  $infectionsByRequestedTime * 0.02;
	$dollarsInFlight = round($infectionsByRequestedTime * calcNoramlizedAvgIncome($data), 2);

	return [
		  "currentlyInfected" => $impact,
		  "infectionsByRequestedTime" => $infectionsByRequestedTime,
		  "severeCasesByRequestedTime" => $severeCasesByRequestedTime,
		  "hospitalBedsByRequestedTime"=> $hospitalBedsByRequestedTime,
		  "casesForICUByRequestedTime" => $casesForICUByRequestedTime,
		  "casesForVentilatorsByRequestedTime" => $casesForVentilatorsByRequestedTime,
		  "dollarsInFlight" => $dollarsInFlight,
	  ];
}



 
function covid19ImpactEstimator($data)
{

  return [
	  "data" => $data,
	  "impact" => impactEstimator($data),
	  "severeImpact" => severeImpactEstimator($data)
  ];
}