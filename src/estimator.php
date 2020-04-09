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

function impactEstimator($data)
{
	$impact = $data["reportedCases"] * 10;
	
	$infectionsByRequestedTime = infectionProjection($impact, $data );
	
	return [
		  "currentlyInfected" => $impact,
		  "infectionByRequestedTime" => $infectionsByRequestedTime
	   ];
}

function severeImpactEstimator($data)
{
	$impact = $data["reportedCases"] * 50;

	$infectionsByRequestedTime = infectionProjection( $impact, $data);
	
	return [
		  "currentlyInfected" => $impact,
		  "infectionByRequestedTime" => $infectionsByRequestedTime
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