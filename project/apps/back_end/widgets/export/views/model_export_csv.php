<?php

header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="export-'.strtolower($database).'-'.strtolower($model).'.csv"');

$columnString = '';
if(count($fieldNames)>0){
	foreach($fieldNames as $fieldName){
		if($fieldInformation[$fieldName]['is_foreign_reference']){
			$fieldName = $fieldInformation[$fieldName]['use_model'];
		}
		$columnString .= '"'.preg_replace('/\"/','""',$fieldName).'",';
	}
	echo substr($columnString,0,-1)."\n\r";
	foreach($results as $result){
		//true value should be fetched through 'get_function'. Reason is when uploading back, 'set_function' will and should be used...
		$rowString = '';
		foreach($fieldNames as $fieldName){
			if($fieldInformation[$fieldName]['is_foreign_reference']){
				$value = $result[$model]->getValueFromKey($fieldInformation[$fieldName]['use_model']);
			} else {
				$value = $result[$model]->{$fieldInformation[$fieldName]['get_function']}();
			}
			$rowString .= '"'.preg_replace('/\"/','""',$value).'",';
		}
		echo substr($rowString,0,-1)."\n\r";
	}
}

?>