<?php

// Download car brand logos from reliable CDN
$brands = [
    'toyota' => 'https://logos-download.com/wp-content/uploads/2016/03/Toyota_logo.png',
    'nissan' => 'https://logos-download.com/wp-content/uploads/2016/03/Nissan_logo.png',
    'ford' => 'https://logos-download.com/wp-content/uploads/2016/03/Ford_logo.png',
    'mercedes' => 'https://logos-download.com/wp-content/uploads/2016/03/Mercedes-Benz_logo.png',
    'honda' => 'https://logos-download.com/wp-content/uploads/2016/03/Honda_logo.png',
    'hyundai' => 'https://logos-download.com/wp-content/uploads/2016/03/Hyundai_logo.png',
    'bmw' => 'https://logos-download.com/wp-content/uploads/2016/03/BMW_logo.png',
    'audi' => 'https://logos-download.com/wp-content/uploads/2016/03/Audi_logo.png',
    'lexus' => 'https://logos-download.com/wp-content/uploads/2016/03/Lexus_logo.png',
    'kia' => 'https://logos-download.com/wp-content/uploads/2016/03/Kia_logo.png',
    'porsche' => 'https://logos-download.com/wp-content/uploads/2016/03/Porsche_logo.png',
    'volkswagen' => 'https://logos-download.com/wp-content/uploads/2016/03/Volkswagen_logo.png',
    'jeep' => 'https://logos-download.com/wp-content/uploads/2016/03/Jeep_logo.png',
    'mitsubishi' => 'https://logos-download.com/wp-content/uploads/2016/03/Mitsubishi_logo.png',
    'chevrolet' => 'https://logos-download.com/wp-content/uploads/2016/03/Chevrolet_logo.png',
];

$outputDir = __DIR__ . '/../public/images/brands/';

if (!is_dir($outputDir)) {
    mkdir($outputDir, 0755, true);
}

foreach ($brands as $name => $url) {
    $outputFile = $outputDir . $name . '.png';
    
    try {
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            ]
        ]);
        
        $imageData = @file_get_contents($url, false, $context);
        
        if ($imageData !== false && strlen($imageData) > 100) {
            file_put_contents($outputFile, $imageData);
            echo "Downloaded: $name.png\n";
        } else {
            echo "Failed: $name (empty response)\n";
        }
    } catch (Exception $e) {
        echo "Failed: $name - " . $e->getMessage() . "\n";
    }
    
    usleep(500000); // 0.5 second delay
}

echo "\nDone!\n";
