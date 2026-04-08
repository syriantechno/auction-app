<?php
$map = [
    'geely' => 'geely.com',
    'byd' => 'byd.com',
    'lexus' => 'lexus.com',
    'genesis' => 'genesis-motor.com',
    'chery' => 'cheryinternational.com',
    'haval' => 'haval-global.com',
    'jetour' => 'jetour.com.cn',
    'maybach' => 'maybach.com',
    'saab' => 'saab.com',
    'citroen' => 'citroen.com',
    'jac' => 'jac.com.cn',
    'skoda' => 'skoda-auto.com',
    'seat' => 'seat.com',
    'opel' => 'opel.com',
    'mg' => 'mgmotor.me',
    'changan' => 'changan.com.cn',
    'exeed' => 'exeed-uae.com',
    'gac' => 'gac-motor.com',
    'hongqi' => 'hongqi-uae.com',
    'lucid' => 'lucidmotors.com',
];

if (!is_dir('public/images/brands')) {
    mkdir('public/images/brands', 0777, true);
}

foreach ($map as $slug => $domain) {
    echo "Processing $slug ($domain)... ";
    $url = "https://logo.clearbit.com/" . $domain . "?size=256";
    try {
        $img = @file_get_contents($url);
        if ($img && strlen($img) > 100) {
            file_put_contents("public/images/brands/" . $slug . ".png", $img);
            echo "Success!\n";
        } else {
            echo "Failed (Empty/Small)\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
echo "Done.\n";
