<?php
// Takimlari al
$takimlar = $_POST['takimlar'] ?? [];

// Geçersiz giriş kontrolü
if (count($takimlar) !== 18) {
    die("Lütfen 18 takim giriniz.");
}

// Her takimin istatistiklerini tutacak dizi
$puan_durumu = [];
foreach ($takimlar as $takim) {
    $puan_durumu[$takim] = [
        'O' => 0,  // Oynadiği maç
        'G' => 0,  // Galibiyet
        'B' => 0,  // Beraberlik
        'M' => 0,  // Mağlubiyet
        'AG' => 0, // Attiği gol
        'YG' => 0, // Yediği gol
        'AV' => 0, // Averaj
        'P' => 0   // Puan
    ];
}

// Maçlari oynat (çift devreli: her takim diğer takimla hem iç hem diş maç yapar)
for ($i = 0; $i < count($takimlar); $i++) {
    for ($j = 0; $j < count($takimlar); $j++) {
        if ($i != $j) {
            $ev_sahibi = $takimlar[$i];
            $deplasman = $takimlar[$j];

            // Rastgele goller (0-5 arasi)
            $ev_gol = rand(0, 5);
            $dep_gol = rand(0, 5);

            // İstatistikleri güncelle
            $puan_durumu[$ev_sahibi]['O']++;
            $puan_durumu[$deplasman]['O']++;

            $puan_durumu[$ev_sahibi]['AG'] += $ev_gol;
            $puan_durumu[$ev_sahibi]['YG'] += $dep_gol;

            $puan_durumu[$deplasman]['AG'] += $dep_gol;
            $puan_durumu[$deplasman]['YG'] += $ev_gol;

            if ($ev_gol > $dep_gol) {
                // Ev sahibi kazandi
                $puan_durumu[$ev_sahibi]['G']++;
                $puan_durumu[$deplasman]['M']++;
                $puan_durumu[$ev_sahibi]['P'] += 3;
            } elseif ($dep_gol > $ev_gol) {
                // Deplasman kazandi
                $puan_durumu[$deplasman]['G']++;
                $puan_durumu[$ev_sahibi]['M']++;
                $puan_durumu[$deplasman]['P'] += 3;
            } else {
                // Beraberlik
                $puan_durumu[$ev_sahibi]['B']++;
                $puan_durumu[$deplasman]['B']++;
                $puan_durumu[$ev_sahibi]['P'] += 1;
                $puan_durumu[$deplasman]['P'] += 1;
            }
        }
    }
}

// Averajlari hesapla
foreach ($puan_durumu as $takim => &$deger) {
    $deger['AV'] = $deger['AG'] - $deger['YG'];
}
unset($deger); // referansi temizle

// Puan durumuna göre sirala
uasort($puan_durumu, function($a, $b) {
    if ($a['P'] != $b['P']) return $b['P'] - $a['P'];
    if ($a['AV'] != $b['AV']) return $b['AV'] - $a['AV'];
    return $b['AG'] - $a['AG'];
});
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Puan Durumu</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
        th { background-color: #ccc; }
        h1 { text-align: center; }
    </style>
</head>
<body>
    <h1>Süper Lig Puan Durumu</h1>
    <table>
        <tr>
            <th>#</th>
            <th>Takim</th>
            <th>O</th>
            <th>G</th>
            <th>B</th>
            <th>M</th>
            <th>AG</th>
            <th>YG</th>
            <th>AV</th>
            <th>P</th>
        </tr>
        <?php
        $sira = 1;
        foreach ($puan_durumu as $takim => $istatistik): ?>
            <tr>
                <td><?= $sira++ ?></td>
                <td><?= htmlspecialchars($takim) ?></td>
                <td><?= $istatistik['O'] ?></td>
                <td><?= $istatistik['G'] ?></td>
                <td><?= $istatistik['B'] ?></td>
                <td><?= $istatistik['M'] ?></td>
                <td><?= $istatistik['AG'] ?></td>
                <td><?= $istatistik['YG'] ?></td>
                <td><?= $istatistik['AV'] ?></td>
                <td><?= $istatistik['P'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>