<?php 
	echo 'started <br/>';
	$file = fopen("data/snapElec.csv","r");
	$itemPerPage = 40;
	$totalFile = 10;

	//read the heading row
	$headRow = fgetcsv($file);

	//find index of the required fields
	$titleInd = array_search('ProductName', $headRow);
	$urlInd = array_search('ProductURL', $headRow);
	$priceInd = array_search('ProductPrice', $headRow);
	$oldPriceInd = array_search('WasPrice', $headRow);
	$imgURLArr = array_keys(preg_grep("/ProductImage/i", $headRow));
	
	/*
	echo "title=".$titleInd."<br/>";
	echo "url=".$urlInd."<br/>";
	echo "price=".$priceInd."<br/>";
	echo "oldPriceInd=".$oldPriceInd."<br/>";
	echo 'ImgUrls='. print_r($imgURLArr) . "<br/>"; //"imgURLInd=".$imgURLArr[0]."<br/>";
	*/

	for($fileNo=1; $fileNo <= $totalFile; $fileNo++) {

		$opJSON = '[';	
		for($lineNo=1; $lineNo <= $itemPerPage; $lineNo++){		
			$productArr = fgetcsv($file);
			$imgUrl = strlen($productArr[$imgURLArr[0]])!= 0 ? $productArr[$imgURLArr[0]] : $productArr[$imgURLArr[1]] ;
			$imgUrl = strlen($imgUrl) != 0 ? $imgUrl : $productArr[$imgURLArr[2]];
			
			$opJSON .= '{'
					.  '"url": "' . $productArr[$urlInd] . '",'
					. '"title": "' . $productArr[$titleInd] . '",'
					. '"imageUrls": [{"url": "' . $imgUrl . '"}],'
					. '"description": "<span class=\"old-price price-cut\"> <i class=\"icon-rupee\"></i>' . $productArr[6] . '</span> <i class=\"icon-rupee\"></i>' . $productArr[4] . '"'
					. '}';

			if($lineNo < $itemPerPage) {
				$opJSON .= ',';
			}
		}

		$opJSON .= ']';
		
		echo $fileNo . 'reading complete, attempting to write <br/>';
		//write to file
		$newFileName = "data/opJSONFiles/data_" . date("MdY-hisa") . "_" . $fileNo . ".json";
		echo 'writing file name = ' . $newFileName;
		$newFile = fopen($newFileName, "w");
		fwrite($newFile, $opJSON);
		fclose($newFile);

		echo '<br/>'. $fileNo .' file writing complete...<br/><br/>';

	}
	
	//close the reading file
	fclose($file);
	
 ?>