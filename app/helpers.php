<?php
function convDateFromDb($date) 
{
	if(empty($date) || !isset($date)) { return ''; }

	$arr = explode('-', $date);
	
	return $arr[2]. '/' .$arr[1]. '/' .$arr[0];
}

function convDateToDb($date) 
{
	if(empty($date) || !isset($date)) { return ''; }

	$arr = explode('/', $date);
	
	return $arr[0]. '-' .$arr[1]. '-' .$arr[2];
}

function convThDateFromDb($date) 
{
	if(empty($date) || !isset($date)) { return ''; }

	$arr = explode('-', $date);
	$year = (int)$arr[0] + 543;
	
	return $arr[2]. '/' .$arr[1]. '/' .$year;
}

function convThDateToDb($date) 
{
	if(empty($date) || !isset($date)) { return ''; }

	$arr = explode('/', $date);
	$year = (int)$arr[2] - 543;
	
	return $year. '-' .$arr[1]. '-' .$arr[0];
}

/** function สำหรับสร้างประเภทหนี้จาก array เป็นสาย string เชื่อมต่อกันด้วย comma */
function getDebtTypeListOfApprovement($appDetails = [], $debttypes = [])
{
	$i = 0;
	$debtTypeLists = '';
	foreach($appDetails as $detail) {
		if($detail->debts) {
			$debtTypeLists .= getDebtTypeName($debttypes, $detail->debts->debt_type_id);

			if($i < count($appDetails) - 1) {
				$debtTypeLists .= ', ';
			}
		}

		$i++;
	}

	return count($appDetails) > 0 ? $debtTypeLists : '';
}

/** function สำหรับดึงชื่อประเภทหนี้จาก array ประเภทหนี้ */
function getDebtTypeName($debttypes = [], $id)
{
	foreach($debttypes as $type) {
		if($type['debt_type_id'] == $id) {
			return $type['debt_type_name'];
		}
	}
}