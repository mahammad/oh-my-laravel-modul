<?php

namespace App\Http\Controllers;

use App\Json;
use Goutte\Client;
use Illuminate\Http\Request;
use function Composer\Autoload\includeFile;

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
}
