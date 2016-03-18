<?php

//Since Correlation needs two arrays, I am hardcoding them
// $array1[0] = 59.3;
// $array1[1] = 61.2;
// $array1[2] = 56.8;
// $array1[3] = 97.55;

// $array2[0] = 565.82;
// $array2[1] = 54.568;
// $array2[2] = 84.22;
// $array2[3] = 483.55;

//To find the correlation of the two arrays, simply call the  
//function Correlation that takes two arrays:

//$correlation = Correlation($array1, $array2);

//Displaying the calculated Correlation:
// print $correlation;

//The functions that work behind the scene to calculate the
//correlation

function Correlation($arr1, $arr2)
{        
    $correlation = 0;
    
    $k = SumProductMeanDeviation($arr1, $arr2);
    $ssmd1 = SumSquareMeanDeviation($arr1);
    $ssmd2 = SumSquareMeanDeviation($arr2);
    
    $product = $ssmd1 * $ssmd2;
    
    $res = sqrt($product);
    
    $correlation = $k / $res;
    
    return $correlation;
}

function SumProductMeanDeviation($arr1, $arr2)
{
    $sum = 0;
    
    $num = count($arr1);
    
    for($i=0; $i<$num; $i++)
    {
        $sum = $sum + ProductMeanDeviation($arr1, $arr2, $i);
    }
    
    return $sum;
}

function ProductMeanDeviation($arr1, $arr2, $item)
{
    return (MeanDeviation($arr1, $item) * MeanDeviation($arr2, $item));
}

function SumSquareMeanDeviation($arr)
{
    $sum = 0;
    
    $num = count($arr);
    
    for($i=0; $i<$num; $i++)
    {
        $sum = $sum + SquareMeanDeviation($arr, $i);
    }
    
    return $sum;
}

function SquareMeanDeviation($arr, $item)
{
    return MeanDeviation($arr, $item) * MeanDeviation($arr, $item);
}

function SumMeanDeviation($arr)
{
    $sum = 0;
    
    $num = count($arr);
    
    for($i=0; $i<$num; $i++)
    {
        $sum = $sum + MeanDeviation($arr, $i);
    }
    
    return $sum;
}

function MeanDeviation($arr, $item)
{
    $average = Average($arr);
    
    return $arr[$item] - $average;
}    

function Average($arr)
{
    $sum = Sum($arr);
    $num = count($arr);
    
    return $sum/$num;
}

function Sum($arr)
{
    return array_sum($arr);
}

//Get Correlation Color Codes
function Correlation_color($correl_value)
{        
	if($correl_value < -0.931) :	{ $correl_color='#FF0000';}
	elseif(($correl_value >=-0.931) and ($correl_value < -0.862)) :  {  $correl_color= '#FF1100'; }
	elseif(($correl_value >=-0.862) and ($correl_value < -0.793)) :  {   $correl_color= '#FF2300'; }
	elseif(($correl_value >=-0.793) and ($correl_value < -0.724)) :  {   $correl_color= '#FF3400'; }
	elseif(($correl_value >=-0.724) and ($correl_value < -0.655)) :  {   $correl_color= '#FF4600'; }
	elseif(($correl_value >=-0.655) and ($correl_value < -0.586)) :  {   $correl_color= '#FF5700'; }
	elseif(($correl_value >=-0.586) and ($correl_value < -0.517)) :  {   $correl_color= '#FF6900'; }
	elseif(($correl_value >=-0.517) and ($correl_value < -0.448)) :  {   $correl_color= '#FF7B00'; }
	elseif(($correl_value >=-0.448) and ($correl_value < -0.379)) :  {   $correl_color= '#FF8C00'; }
	elseif(($correl_value >=-0.379) and ($correl_value < -0.31)) :  {   $correl_color= '#FF9E00'; }
	elseif(($correl_value >=-0.31) and ($correl_value < -0.241)) :  {   $correl_color= '#FFAF00'; }
	elseif(($correl_value >=-0.241) and ($correl_value < -0.172)) :  {   $correl_color= '#FFC100'; }
	elseif(($correl_value >=-0.172) and ($correl_value < -0.103)) :  {   $correl_color= '#FFD300'; }
	elseif(($correl_value >=-0.103) and ($correl_value < -0.034)) :  {   $correl_color= '#FFE400'; }
	elseif(($correl_value >=-0.034) and ($correl_value < 0.0349)) :  {   $correl_color= '#FFF600'; }
	elseif(($correl_value >=0.0349) and ($correl_value < 0.104)) :  {   $correl_color= '#F7FF00'; }
	elseif(($correl_value >=0.104) and ($correl_value < 0.173)) :  {   $correl_color= '#E5FF00'; }
	elseif(($correl_value >=0.173) and ($correl_value < 0.242)) :  {   $correl_color= '#D4FF00'; }
	elseif(($correl_value >=0.242) and ($correl_value < 0.311)) :  {   $correl_color= '#C2FF00'; }
	elseif(($correl_value >=0.311) and ($correl_value < 0.38)) :  {   $correl_color= '#B0FF00'; }
	elseif(($correl_value >=0.38) and ($correl_value < 0.449)) :  {   $correl_color= '#9FFF00'; }
	elseif(($correl_value >=0.449) and ($correl_value < 0.518)) :  {   $correl_color= '#8DFF00'; }
	elseif(($correl_value >=0.518) and ($correl_value < 0.587)) :  {   $correl_color= '#7CFF00'; }
	elseif(($correl_value >=0.587) and ($correl_value < 0.656)) :  {   $correl_color= '#6AFF00'; }
	elseif(($correl_value >=0.656) and ($correl_value < 0.725)) :  {   $correl_color= '#58FF00'; }
	elseif(($correl_value >=0.725) and ($correl_value < 0.794)) :  {   $correl_color= '#47FF00'; }
	elseif(($correl_value >=0.794) and ($correl_value < 0.863)) :  {   $correl_color= '#35FF00'; }
	elseif(($correl_value >=0.863) and ($correl_value < 0.932)) :  {   $correl_color= '#24FF00'; }
	elseif(($correl_value >=0.932) and ($correl_value < 1.001)) :  {   $correl_color= '#12FF00'; }
	endif;
    return $correl_color;
}
?>