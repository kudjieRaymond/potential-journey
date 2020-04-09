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
	return (35/100) * $totalBeds;
}

function impactEstimator($data)
{
	$impact = $data["reportedCases"] * 10;
	
	$infectionsByRequestedTime = infectionProjection($impact, $data );
	$severeCasesByRequestedTime = $infectionsByRequestedTime * 0.15;
	$hospitalBedsByRequestedTime = calcBedAvailability($data["totalHospitalBeds"]) - $severeCasesByRequestedTime;
	
	return [
		  "currentlyInfected" => $impact,
		  "infectionsByRequestedTime" => $infectionsByRequestedTime,
		  "severeCasesByRequestedTime" => $severeCasesByRequestedTime,
		  "hospitalBedsByRequestedTime"=> $hospitalBedsByRequestedTime,
	   ];
}

function severeImpactEstimator($data)
{
	$impact = $data["reportedCases"] * 50;

	$infectionsByRequestedTime = infectionProjection( $impact, $data);
	$severeCasesByRequestedTime = $infectionsByRequestedTime * 0.15;
	$hospitalBedsByRequestedTime = calcBedAvailability($data["totalHospitalBeds"]) - $severeCasesByRequestedTime;
	
	return [
		  "currentlyInfected" => $impact,
		  "infectionsByRequestedTime" => $infectionsByRequestedTime,
		  "severeCasesByRequestedTime" => $severeCasesByRequestedTime,
		  "hospitalBedsByRequestedTime"=>$hospitalBedsByRequestedTime,
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