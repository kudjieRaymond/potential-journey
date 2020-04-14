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

function calcBedAvailability($totalBeds, $severity)
{
	$availability = (0.35 * $totalBeds) - $severity ;
	
	return (int)$availability;
}


function moneyLost($infectionsByRequestedTime, $data)
{
	$days = normalizeDuration($data["timeToElapse"], $data["periodType"]);
	$avgIncome = $data["region"]["avgDailyIncomePopulation"] * $data["region"]["avgDailyIncomeInUSD"] ;

	return (int)(($infectionsByRequestedTime * $avgIncome)/$days) ;
}

function impactEstimator($data)
{
	$impact = $data["reportedCases"] * 10;
	
	$infectionsByRequestedTime = infectionProjection($impact, $data );
	$severeCasesByRequestedTime =(int) ($infectionsByRequestedTime * 0.15);
	$hospitalBedsByRequestedTime = calcBedAvailability($data["totalHospitalBeds"],  $severeCasesByRequestedTime);
	$casesForICUByRequestedTime = (int) ($infectionsByRequestedTime * 0.05);
	$casesForVentilatorsByRequestedTime = (int) ($infectionsByRequestedTime * 0.02);
	$dollarsInFlight = moneyLost($infectionsByRequestedTime, $data);
	
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


function severeImpactEstimator($data)
{
	$impact = $data["reportedCases"] * 50;

	$infectionsByRequestedTime = infectionProjection( $impact, $data);
	$severeCasesByRequestedTime = (int)($infectionsByRequestedTime * 0.15);
	$hospitalBedsByRequestedTime = calcBedAvailability($data["totalHospitalBeds"], $severeCasesByRequestedTime);
	$casesForICUByRequestedTime = (int)($infectionsByRequestedTime * 0.05);
	$casesForVentilatorsByRequestedTime =  (int)($infectionsByRequestedTime * 0.02);
	$dollarsInFlight = moneyLost($infectionsByRequestedTime , $data);

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