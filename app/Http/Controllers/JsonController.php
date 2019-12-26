<?php

namespace App\Http\Controllers;

use App\Json;
use Goutte\Client;
use Illuminate\Http\Request;

class JsonController extends Controller
{
    public function index()
    {
        $result = null;
        $title = 'Json Dönüştürücü';
        return view('jsonconvertor.index', compact('title', 'result'));
    }

    public function create()
    {
        $title = 'Json Dosya Oluşturucu';
        $output = \request()->get('json-data');

        file_put_contents(base_path('public/json/data.json'), stripslashes($output));
        return view('jsonconvertor.show', compact('output'));
    }

    public function store(Request $request)
    {
    }

    public function show(Json $json)
    {
    }

    public function edit(Json $json)
    {
    }

    public function update(Request $request, Json $json)
    {
    }

    public function destroy(Json $json)
    {
    }

    public function table()
    {
        $title = 'Json Dönüştürücü';
        $method = \request()->get('method');
        $uri = \request()->get('uri');
        $html_tag_st = \request()->get('html_tag1');
        $html_tag_nd = \request()->get('html_tag2');
        $result = null;
        $crawler = (new Client)->request($method, $uri);
        $keys = $this->getData($crawler, $html_tag_st);
        $values = $this->getData($crawler, $html_tag_nd);
        $j = 1;
        for ($a = 0; $a < count($values) / count($keys); $a++) {
            for ($i = 1; $i < count($keys); $i++)
                $result[$a][$keys[$i]] = $values[$j++];
            $j++;
        }

        //json_encode($result);
        return view('jsonconvertor.index', compact('title', 'result'));
    }

    public function response()
    {
        $result = null;
        $tag2 = ['#pair_1 td', '#pair_2 td', '#pair_3 td', '#pair_4 td', '#pair_5 td', '#pair_6 td', '#pair_7 td'];
        $crawler = (new Client)->request('get', 'https://tr.investing.com/commodities/softs');
        $output1 = $this->filterData($crawler, '#cross_rate_1 thead tr th', '#cross_rate_1 tbody tr td');
        $output3 = $this->filterData($crawler, '#cross_rate_3 thead tr th', '#cross_rate_3 tbody tr td');
        $output2 = $this->filterDataNd($crawler, '#BarchartDataTable thead tr th', $tag2);
        $result[0] = $output1;
        $result[1] = $output2;
        $result[2] = $output3;
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /*<table id="cross_rate_3"(.*?)><thead><tr><th>(.*?)</th>(.*?)</tr></thead><tbody>(.*?)</tbody></table>*/


    private function filterData($crawler, $tag1, $tag2)
    {
        $keys = $this->getData($crawler, $tag1);
        $values = $this->getData($crawler, $tag2);
        $j = 1;
        for ($a = 0; $a < count($values) / count($keys); $a++) {
            for ($i = 1; $i < count($keys); $i++)
                $result[$a][$keys[$i]] = $values[$j++];
            $j++;
        }
        return $result;
    }

    private function filterDataNd($crawler, $tag1, $tag2 = array())
    {
        $values = array();
        $keys = $this->getData($crawler, $tag1);
        foreach ($tag2 as $item)
            array_push($values, $this->getData($crawler, $item));
        for ($a = 0; $a < count($values); $a++) {
            for ($i = 1; $i < count($keys); $i++)
                $result[$a][$keys[$i]] = $values[$a][$i];
        }
        return $result;
    }


    public function getData($crawler, $html_tag)
    {
        $data = null;
        $data = $crawler->filter($html_tag)->each(function ($node) {
            return $node->text();
        });
        return $data;
    }

    /*==========================={ For PHP}=====================================*/
    public function responsephp()
    {
        $context = stream_context_create(array("http" => array("header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36")));
        $html = file_get_contents('https://tr.investing.com/commodities/softs', false, $context);

        /* Birinci Tablo Değerleri için */
        preg_match_all('@<table id="cross_rate_1" tablesorter class="genTbl closedTbl crossRatesTbl"><thead><tr>(.*?)</tr></thead>(.*?)</table>@', $html, $keys1);
        /* İkinci Tablo Değerleri için*/
        preg_match_all('@<table meats id="BarchartDataTable" class="genTbl closedTbl crossRatesTbl"><thead><tr>(.*?)</tr></thead>(.*?)</table>@', $html, $keys2);
        /* Üçüncü Tablo Değerleri için*/
        preg_match_all('@<table id="cross_rate_3" tablesorter class="genTbl closedTbl crossRatesTbl"><thead><tr>(.*?)</tr></thead>(.*?)</table>@', $html, $keys3);


        /* Toblolardan Temizlenmiş verilerin dizi haline dönüşümü*/
        $jsonResult[] = $this->makeArray(($this->cleanArray($keys1[0][0])), 9);
        $jsonResult[] = $this->makeArray(($this->cleanArray($keys2[0][0])), 9, true);
        $jsonResult[] = $this->makeArray(($this->cleanArray($keys3[0][0])), 9);
        return json_encode($jsonResult, JSON_UNESCAPED_UNICODE);
    }

    function makeArray($data, $colon_size, bool $control = false)
    {
        $data = array_values(array_filter($data, function ($value) {
            if ($value == '-' || $value == ' - ')
                return false;
            return true;
        }));
        $countData = 0;
        $jdata = 0;
        $colon_size_temp = $colon_size;
        $result = array();
        if ($control)
            $data = array_chunk($this->cleanData($data), $colon_size);
        else
            $data = array_chunk($data, $colon_size);
        for ($k = 1; $k < count($data); $k++) {
            for ($i = 0; $i < $colon_size; $i++) {
                if ($countData == $colon_size_temp)
                    $countData = 0;
                $result[$jdata][ltrim($data[0][$countData++])] = ltrim($data[$k][$i]);
            }
            $jdata++;
        }
        return $result;
    }

    function cleanArray($data)
    {
        if (strpos($data, '<td class="left noWrap"></td>') !== false) {
            // Boş Tarih Alanlarını 0 ile dolduruyor
            $data = str_replace('<td class="left noWrap"></td>', '<td class="left noWrap">--</td>', $data);
        }
        // Tablonun ilk kolonundakı iconları temizliyor
        $data = str_replace('&nbsp;', '-',$data);
        // Dizide bulunan < karakterlerinin önüne - karakterini eklemek,
        // daha sonra - karakterine göre dizide topluyor daha sonra html tag temizliği yapıyoruz
        $clear = explode(";", strip_tags(str_replace("<", ";<", $data)));
        return array_values(array_filter($clear));
    }

    function cleanData($dataArray)
    {
        $j = 8;
        $count = 0;
        $body_data = array();
        $head_data = array();
        $new_data = array();
        for ($n = 0; $n < count($dataArray); $n++) {
            if ($n < 9)
                $head_data[] = $dataArray[$n];
            else
                $body_data[] = $dataArray[$n];
        }
        for ($m = 0; $m < count($body_data);) {
            if ($m == $j) {
                for ($mi = 0; $mi < 5; $mi++)
                    $new_data[$count][$mi] = $body_data[$m++];
                $j = $j + 13;
                $new_data[$count] = str_replace("-/-", "/", implode('', $new_data[$count]));
            } else
                $new_data[$count] = $body_data[$m++];
            $count++;
        }
        return array_merge($head_data, $new_data);
    }

}
