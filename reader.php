<?php
// header("Content-Type:text/plain");

// dołączamy niezbędne biblioteki
require_once('vendor/autoload.php');
require_once('simple_html_dom.php');

// odczyt pliku dane xlsx i wyszukanie pasującego wyrażenia, 
// w zamyśle wyszukiwanie poprzez formularz html
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load('dane.xlsx');

$data = array(1, $spreadsheet->getActiveSheet()
            ->toArray(null, true, true, true));

foreach($data as $dat) {
    foreach((array)$dat as $da) {
        if($da['B'] == "8-port GbE Smart Managed PoE Switch with GbE Uplink") {
            $name = $da['A'];
        }
    }
}

// wyszukiwanie na stronie google
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, 'http://www.google.pl/search?hl=pl&q='.$name);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($curl);
curl_close($curl);

$re = '/<a href="(.*?)">(.*?)<\/a>/';
preg_match_all($re, $result, $matches, PREG_SET_ORDER, 0);

$re1 = '/https:\/\/www.zyxel.com\/pl\/pl\/[\w]*\/[\w-]*\//';
preg_match_all($re1, $result, $matches1, PREG_SET_ORDER, 0);

foreach($matches1 as $match1) {
    foreach($match1 as $mat)
    $link = $mat;
}

// wyszukiwanie strony ze specyfikacją na stronie zyxel.com
$html = file_get_html($link . "specification");

foreach($html->find('table tr td') as $e) {
    $arr[] = trim($e->innertext);
}

echo "<table>";
foreach($arr as $ar) {
    echo "<tr>";
    echo $ar;
    echo "<tr>";
}
echo "</table>";






