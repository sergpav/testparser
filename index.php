<?php 
ini_set('display_errors', 1); 
error_reporting(E_ALL);

function saveFile($fileName, $data) {
  $file = fopen('public/'.$fileName, 'w');// open or create file to save data
  fwrite($file, $data); // saving data
  fclose($file);
}

$link = "http://www.t.zp.ua/%D0%B7%D0%B0%D0%B3%D1%80%D0%B0%D0%BD%D0%BF%D0%B0%D1%81%D0%BF%D0%BE%D1%80%D1%82";
$res = file_get_contents($link); // get the url data
if($res) {
  saveFile('file.html', $res);
} else {
  echo "Unable to access URL";
}

$doc = new DOMDocument();
@$doc->loadHTML(file_get_contents('public/file.html')); //add @ symbol to avoid warnings of parsing
$doc->preserveWhiteSpace = false;
$finder = new DomXPath($doc);
$classname = 'entry-content'; //class name of element that looking for
$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]"); //finding element with class name
$plainText = $nodes->item(0)->nodeValue; //saving plaintext to variable


function count_words($text) {
  $text = mb_strtolower($text, 'UTF-8');
  $text = htmlentities($text);
  $text = str_replace(array('—', '-', '&nbsp;'), '', $text); //trim useless data
  $cnt = array_count_values(str_word_count($text, 1, "АаБбВвГгҐґДдЕеЁёЄєЖжЗзИиІіЇїЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЬьЪъЫыЮюЬьЭэЯя")); // spelling for str_word_count
  return $cnt;
}

$wordsArr = count_words($plainText);
$data = json_encode($wordsArr, JSON_UNESCAPED_UNICODE ); //store parced data to json
saveFile('data.json',$data); //saving parsed data
