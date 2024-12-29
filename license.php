<?php
// Konfigurasi GitHub API
$githubApiUrl = "https://api.github.com/repos/AangQwerty/Multi/contents/tools/api/license.json";
$githubToken = "https://api.github.com/repos/AangQwerty/Auth-Token/contents/content/token.json";

// Header permintaan API
$headers = [
    "Host": "https://api.github.com/",
    "User-Agent": "Mozilla/5.0 (Linux; Android 13; 21121119SG Build/TP1A.220624.014; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/131.0.6778.135 Mobile Safari/537.36",
];
if (!empty($githubToken)) {
    $headers[] = "Authorization: token $githubToken";
}

// Fungsi untuk mengambil data JSON dari GitHub
function fetchJsonFromGitHub($url, $headers) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        die("[!] Gagal mengambil data. Kode HTTP: $httpCode");
    }

    $data = json_decode($response, true);
    if (isset($data['content'])) {
        return json_decode(base64_decode($data['content']), true);
    }
    die("[!] Data tidak ditemukan.");
}

// Ambil daftar lisensi dari GitHub
$jsonData = fetchJsonFromGitHub($githubApiUrl, $headers);
if (!isset($jsonData['licenses'])) {
    die("[!] Format JSON tidak valid.");
}

// Lisensi pengguna
$userLicense = "";

$licenseFound = null;
foreach ($jsonData['licenses'] as $license) {
    if ($license['license'] === trim($userLicense)) {
        $licenseFound = $license;
        break;
    }
}

if ($licenseFound) {
    $currentDate = date("Y-m-d");
    if ($currentDate <= $licenseFound['expires_at']) {
        echo "Lisensi valid!\n";
        echo "Status: " . $licenseFound['status'] . "\n";
        echo "Berlaku hingga: " . $licenseFound['expires_at'] . "\n";
    } else {
        echo "Lisensi kedaluwarsa pada " . $licenseFound['expires_at'] . "\n";
    }
} else {
    echo "Lisensi tidak valid!\n";
}