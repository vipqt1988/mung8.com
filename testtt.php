<?php

$accounts = [
  ["name" => "Acc 1", "token" => "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvZGF0YS50cmFmZm1vbmV0aXplci5jb21cL2FwaVwvYXV0aFwvbG9naW4iLCJpYXQiOjE3NzQwMDIzNTEsImV4cCI6MTc3NDYwNzE1MSwibmJmIjoxNzc0MDAyMzUxLCJqdGkiOiI3VmlDbXFiTVdNdVBHbER0Iiwic3ViIjoxNjkxMzc0LCJwcnYiOiI4MDE2ZDQxNmFjYTkyODY1Zjg4ZTU4ODM0MzljNjk5MWYzODM0Y2Y1In0.js4CneuheMRQ0zKQ4siDp8S8_EzLKM8kzDW9R02aEOM"],
  ["name" => "Acc 2", "token" => "Bearer DÁN_TOKEN_2"],
  ["name" => "Acc 3", "token" => "Bearer DÁN_TOKEN_3"],
  ["name" => "Acc 4", "token" => "Bearer DÁN_TOKEN_4"]
];

function getBalance($token) {
  $url = "https://data.traffmonetizer.com/api/app_user/get_balance";

  $opts = [
    "http" => [
      "method" => "GET",
      "header" => "Authorization: $token\r\n"
    ]
  ];

  $context = stream_context_create($opts);
  $response = @file_get_contents($url, false, $context);

  if ($response === FALSE) {
    return ["error" => "Request failed"];
  }

  return json_decode($response, true);
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<style>
body { font-family: Arial; background:#f2f3f5; padding:20px; }
.grid { display:flex; gap:20px; flex-wrap:wrap; }
.card {
  background:white; padding:15px; border-radius:10px;
  width:220px; box-shadow:0 4px 10px rgba(0,0,0,0.1);
}
.balance { font-size:24px; color:#2d7df6; }
.error { color:red; font-size:14px; }
</style>
</head>
<body>

<h2>💰 Dashboard</h2>

<div class="grid">

<?php foreach ($accounts as $acc):

  $data = getBalance($acc['token']);

  $balance =
    $data['balance'] ??
    $data['data']['balance'] ??
    $data['result']['balance'] ??
    $data['earning'] ??
    null;

  if ($balance === null) {
?>

<div class="card">
  <div><?= $acc['name'] ?></div>
  <div class="error">Lỗi lấy dữ liệu</div>
</div>

<?php
  } else {
    $total += $balance;
?>

<div class="card">
  <div><?= $acc['name'] ?></div>
  <div class="balance">$<?= number_format($balance, 4) ?></div>
</div>

<?php } endforeach; ?>

</div>

<h3>👉 Tổng: $<?= number_format($total, 4) ?></h3>

</body>
</html>
