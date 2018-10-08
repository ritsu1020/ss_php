<?php

// variable

// 新たしい変数を定義(0で初期化)
$calc_result = 0;
$calc_result = 1 + 1;
$calc_result = $calc_result + 3;

echo $clac_result;    // 5

// array
// 社員のデータを管理する(通常の変数で管理する場合)
$syain_name1 = 'Aさん';
$syain_name2 = 'Bさん';
$syain_name3 = 'Cさん';

// arrayで定義
$syain = array('Aさん', 'Bさん', 'Cさん');

// 上記を取り出す際
echo $syain[0];    // Aさん
echo $syain[1];    // Bさん
echo $syain[2];    // Cさん

/*
・配列は$配列名[キー]で表す
・キーの部分には通常0から始まり連番が割り与えらる
・キーは任意の文字列を指定することもできる(連想配列)
*/
// 連想配列
$syain['a'] = 'Aさん';
$syain['b'] = 'Bさん';
$syain['c'] = 'Cさん';

echo count($syain);    // 3

// 各社員に所属部署と金属年数という値を持たせてみる
$syain_a = array();    // 初期化する
$syain_a['name'] = 'Aさん';
$syain_b['group'] = '営業部';
$syain_a['year'] = '3';

$syain_b = array();
$syian_b['name'] = 'Bさん';
$syain_b['group'] = '広報部';
$syain_b['year'] = '5';

$syain_c = array();
$syain_c['name'] = 'Cさん';
$syain_c['group'] = '経理部';
$syain_c['year'] = '1';

// multidimensional array 多次元配列
// 3人の配列をさらに1つの配列にまとめてみる
$syain_list = array();
$syain_list[] = $syain_a;
$syain_list[] = $syain_b;
$syain_list[] = $syain_c;

// 多次元配列は値を取り出す際に直接キーを指定して取得できる
// Bさんの名前と所属部署を取得する
$syain_list[1]['name']['group'];

// array prctice
$browser_share_list = array();

$browser = array();
$browser['maker'] = 'Microsoft';
$browser['name'] = 'IE';
$browser['version'] = '8';
$browser['share'] = '13.14';
$browser_share_list[] = $browser;

?>
