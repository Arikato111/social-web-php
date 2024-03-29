<?php
$Notfound = import('./components/api/Notfound');
$db = new Database;

Wexpress::post('*', function () use ($db) {
    $googleToken = $_POST['google-token'] ?? "";
    if (($_SESSION['usr'] ?? false) || ($googleToken ?? false)) {
        $usr = $db->getUser_ByID($_SESSION['usr']);
        $checkGoogleToken = $db->getUser_ByGoogle($googleToken);

        if (empty($usr['google-token']) && (!$checkGoogleToken)) {
            $db->pushGoogleToken($_SESSION['usr'], $googleToken);
            Res::status(200);
            Res::json([
                'status' => 1,
                'message' => 'connect is successfuly'
            ]);
            return;
        } else {
            Res::status(200);
            Res::json([
                'status' => 0,
                'message' => 'this account is connected'
            ]);
            return;
        }
    }
    Res::status(200);
    Res::json([
        'status' => 0,
        'message' => 'bad request'
    ]);
});

Wexpress::all('*', $Notfound);
